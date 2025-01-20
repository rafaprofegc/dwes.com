<?php
/*
    SERIALIZACIÓN DE OBJETOS
    ------------------------

    * Serialización:
        - Proceso de convertir un objeto en una cadena de caracteres.
        - Útil para almacenar objetos en BBDD o archivos.
        - La función integrada serialize() realiza esta conversión objeto->cadena
        - La función integrada unserialize() realiza el proceso contrario.
          convirtiendo la cadena en el objeto.

        - La serialización se emplea en las sesiones PHP, al guardar objetos
          como variables de sesión.
          PHP serializa, al añadir un objeto como variable de sesión ( en $_SESSION )
          y deserializa cuando se cargan las variables de sesión (al invocar session_start() )

        IMPORTANTE: La definición de la clase de objeto tiene que estar disponible antes
        de que se realice la desearialización. De lo contrario crea un objeto de la clase
        StdClass que es totalmente inútil.

    * Método mágico __sleep()
        - Se invoca por serialize() justo antes de la serialización para preservar
          el estado del objeto, es decir, las propiedades del objeto.
        - El método mágico __serialize() se invoca también por serialize(), y tiene
          precedencia sobre __sleep(). Si existe __serialize() no se invoca __sleep().
        - Este método obligatoriamente devuelve un array con las propiedades PÚBLICAS,
          ya que serialize() no tiene acceso a propiedades protegidas o privadas.
        - El método debe hacer tareas de limpieza antes de la serialización, como
          confirmar cambios en la BBDD o cerrar archivos.

    * Método mágico __wakeup()
        - Al invocar la función unserialize() sobre un objeto serializado, se invoca
          __wakeup() para restaurar el objeto.
        - El método mágico __unserialize() se invoca automáticamente por unserialize()
          y tiene precedencia sobre __wakeup().
        - No tiene argumentos ni devuelve valor.
        - Se emplea para restaurar objetos en variables de sesión, y además, restaura
          en el objeto recursos (conexiones BBDD o punteros a archivo) que se puedan
          necesitar al deserializar el objeto.

    Ejemplo:
        - Clase Usuario que tiene login, nombre, perfil y un archivo log donde se registra
          su actividad.
        - Script para autenticar un usuario. Si tiene éxito creo un objeto de la clase
          Usuario y lo almaceno como variable de sesión.
        - Script para que el usuario acceda a su zona restringida, donde recuperamos la
          variable de sesión (el objeto Usuario) y mostramos sus propiedades
*/
session_start();

date_default_timezone_set("Europe/Madrid");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("Usuario.php");

function Error(string $mensaje, string $url, string $enlace ): void {
  inicio_html("Error de autenticación", ["/estilos/general.css"]);
  echo "<header>Serialización de objetos en PHP</header>";
  echo "<h3>Error de la aplicación</h3>";
  echo "<p>$mensaje</p>";
  echo "<p><a href='$url'>$enlace</a></p>";
  fin_html();
  exit(1);
}

function autentica_usuario(string $login, string $password ): mixed {
  $usuarios = ['manuel@gmail.com' => ['nombre' => "Manuel García López", 
                                      'password' => password_hash("manuel", PASSWORD_DEFAULT),
                                      'perfil'   => Usuario::PERFIL_ADM ], 
               'maria@hotmail.com' => ['nombre' => "María Sánchez Martínez", 
                                      'password' => password_hash("maria", PASSWORD_DEFAULT),
                                      'perfil'   => Usuario::PERFIL_ESTANDAR ],
               'javier@gmail.com' => ['nombre' => "Javier González Márquez", 
                                      'password' => password_hash("javier", PASSWORD_DEFAULT),
                                      'perfil'   => Usuario::PERFIL_INVITADO]                                                                              
  ];

  if( array_key_exists($login, $usuarios) ) {
    if( password_verify($password, $usuarios[$login]['password']) ) {
      return $usuarios[$login];
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }
}
if( $_SERVER['REQUEST_METHOD'] == "POST") {
  
  $login = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $login = filter_var($login, FILTER_VALIDATE_EMAIL);

  if( !$login ) {
    Error("El login de usuario no tiene el formato adecuado", $_SERVER['PHP_SELF'], "Volver a intentarlo");
  }

  $password = $_POST['password'];

  $datos_usuario = autentica_usuario($login, $password);
  if( $datos_usuario ) {
    // Autenticación con éxito
    $objeto_usuario = new Usuario($login, $datos_usuario['nombre'], $datos_usuario['perfil'], "$login.log");
    $objeto_usuario->registraActividad("Usuario autenticado con éxito");

    $_SESSION['usuario'] = $objeto_usuario;

    inicio_html("Serialización de objetos en PHP", ["/estilos/general.css"]);
    echo "<header>Serialización de objetos en PHP</header>";
    echo "<h3>Zona de usuarios autenticados</h3>";
    echo "<p>Bienvenido, {$objeto_usuario->nombre}. Se autenticado con éxito y puede ir a su zona</p>";
    echo "<p><a href='/ra6/poo/03serializacion_zona.php'>Puede ir a la zona del perfil {$objeto_usuario->perfil}</p>";
    fin_html();
  }
  else {
    Error("Autenticación fallida", $_SERVER['PHP_SELF'], "Volver a intentarlo");
  }
}
elseif( $_SERVER['REQUEST_METHOD'] == "GET" ) {
  inicio_html("Serialización de objetos en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);
  echo "<header>Serialización de objetos en PHP</header>";
?>
  <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <fieldset>
      <legend>Autenticación de usuario</legend>
      <label for="login">Login</label>
      <input type="email" name="email" id="email" size="20" required>

      <label for="password">Password</label>
      <input type="password" name="password" id="password" size="10" required>
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
  </form>
<?php
}


?>