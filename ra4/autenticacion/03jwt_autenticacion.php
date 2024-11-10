<?php

/*
    Autenticación con formulario
    ----------------------------

    - Se envía un formulario HTTP con POST con el usuario y la contraseña

    - Se verifica el usuario y su contraseña. 

    - Para el cifrado de contraseña usamos password_hash() y password_verify() para
    verificarla 

    - Por defecto, PASSWORD_DEFAULT utiliza el algoritmo BCRYPT con un coste de 10
*/

session_start(['gc_maxlifetime' => 60 * 60]);

date_default_timezone_set("Europe/Madrid");

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

$usuarios = ['manuel@hotmail.com' => ['nombre' => 'Manuel García',
                                      'password' => password_hash("abc123", PASSWORD_DEFAULT),
                                      'perfil' => 'admin'],
             'maria@gmail.com' => ['nombre' => 'María López',
                                     'password' => password_hash("123abc", PASSWORD_DEFAULT),
                                     'perfil' => 'usuario']                                     
];

inicio_html("Autenticación por formulario", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Autenticación de formulario</header>";

/*
foreach($usuarios as $clave => $valor) {
    echo "$clave -> {$valor['password']}<br>";
}
*/

function autentica_usuario($login, $clave) {
    global $usuarios;

    if( !array_key_exists($login, $usuarios) ) {
        return false;
    }
    
    $password = $usuarios[$login]['password'];
    if( password_verify($clave, $password) ) {
        return true;
    }
    else {
        return false;
    }
}
if( $_SERVER['REQUEST_METHOD'] == "POST") {
    // Autenticamos al usuario

    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_EMAIL);
    $login = filter_var($login, FILTER_VALIDATE_EMAIL);

    $clave = $_POST['clave'];

    if( autentica_usuario($login, $clave) ) {
        // Autenticación ha tenido éxito
        // Empieza la generación del token JWT

        // Genero el array con los datos de usuario

        $usuario = [
            'id'       => $login,
            'username' => $usuarios[$login]['nombre'],
            'role'     => $usuarios[$login]['perfil']
        ];

        if( file_exists("03clave.txt") ) {
            $fichero_clave = fopen("03clave.txt", "r");
            $clave = fgets($fichero_clave);
            fclose($fichero_clave);
        }
        else {
            $clave = "abc123";
        }

        $jwt = generar_token($usuario, $clave);

        echo "<p>El token generado: $jwt</p>";

        // El tiempo de validez del jwt es 1 hora
        $expire = time() + 60 * 60;

        // Se establece la cookie para enviar el jwt al cliente
        // setcookie("jwt", $jwt, $expire, "/", "dwes.com", true, true);
        setcookie("jwt", $jwt, $expire, "/", "dwes.com");
        
        echo "<p>Usuario autenticado. Vaya a la <a href='03jwt_bienvenido.php'>zona restringida</a></p>";        
    }
    else {
        // La autenticación no ha tenido éxito
        echo "<h3>Fallo en la autenticación</h3>";
        echo "<p><a href='" . $_SERVER['PHP_SELF'] . "'>Inténtelo de nuevo</a></p>";
    }
}
elseif( $_SERVER['REQUEST_METHOD'] == "GET" ) {
?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <img src="usuario.png">
    <fieldset>
        <legend>Introduzca sus credenciales de usuario</legend>
        <label for="login">Login</label>
        <input type="text" name="login" id="login" required size="10">

        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" required size="10">

    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Iniciar sesión">
</form>
<?php
}
fin_html();
?>