<?php
    /*
        - PHP tiene soporte para gestionar las sesiones y proporcionar variables
          persistentes.
        
        - Las variables (de sesión) son accesibles desde diferentes páginas (scripts)
          durante la sesión de usuario.

        - Adecuadas para formularios multipágina, guardar datos entre peticiones.

        - Cada sesión tiene un ID de sesión único el cual se pasa como cookie al
          cliente, los cuales se devuelven en peticiones posteriores.

        - Cada sesión tiene un almacen de datos para las variables de sesión.

        - Funcionamiento básico:
            - Al iniciar la aplicación en el primer script se incova session_start().
            
            - Si hay una sesión previa recupera el PHPSESSID (cookie) y carga el array
              superglobal $_SESSION

            - Si no hay una petición previa, se crea una y se le asigna un ID de sesión.

            - En el array $_SESSION usamos la clave como nombre de variable

            - Cierre de una sesión:
                - Al cerrar el navegador.
                - Si se cierra la sesión explícitamente por el usuario:
                    - Borrar el PHPSESSID (se borra la cookie)
                    - Se invoca session_destroy();

            - El recolector de basura. Si alguna variable de $_SESSION no ha sido
              referenciada durante algún tiempo, la variable se borra.

            - Para configurar el recolector de basura hay unas directivas de configuración
              en el php.ini

    */   

// Lo primero que se hace en un script php que contemple sesiones
session_start();

date_default_timezone_set("Europe/Madrid");

// Añadimos dos variables de sesión
if( !isset($_SESSION['instante']) ) {
    $_SESSION['instante'] = time();
    $_SESSION['cesta'] = [];
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once("01sesion_include.php");

inicio_html("Sesiones en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Mi cesta de Navidad</header>";
ver_datos_sesion();

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    $operacion = filter_input(INPUT_GET, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
    if( $operacion == 'cerrar') {
        cerrar_sesion();
        ver_datos_sesion();
    }
}
?>

<form action="/ra4/sesiones/01sesion_datos.php" method="POST">
    <fieldset>
        <legend>Datos personales</legend>

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" size="30" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" size="30" required>

    </fieldset>
    <input type="submit" name="operacion" value="Añadir a la cesta">
</form>
<?php
fin_html();
?>