<?php
/*
Páginas autoprocesadas:
    - Si se recibe una petición GET, se genera el formulario.
      El atributo action tiene el valor ...

    - Si se recibe una petición POST, se recogen los datos del formulario
      y se procesa la petición, generando la salida.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");

inicio_html("Páginas autoprocesadas", 
                ["/estilos/general.css", "/estilos/formulario.css"]);

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    // Genero el formulario ?>
        <header>Solicitud de empleo</header>
        <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
            <!-- Información personal --> 
            <fieldset>
                <legend>Datos personales</legend>
                <label for="nombre">Nombre completo</label>
                <input type="text" name="nombre" id="nombre" size="50" required 
                    placeholder="Introduce aquí tu nombre completo">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" size="40" required>

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" size="10" required

            </fieldset>
            <input type="submit" name="operacion" value="Enviar">
        </form>
<?php
}

/*
    Formas de saber si un formulario se ha enviado (si ha llegado):
        - Comprobar el método en $_SERVER['REQUEST_METHOD]
          Si el formulario se envía con POST funciona, pero si se
          envía con GET no funciona.

        - Comprobar si hay datos en $_POST o $_GET. Con la función isset()
          me dice si una variable está definida

*/

//if( $_SERVER['REQUEST_METHOD'] == "POST") {
if( isset($_POST['nombre']) ) {
    // Recoger datos del formulario y procesar la respuesta
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    $operacion = $_POST['operacion'];

    echo "Nombre: $nombre<br>";
    echo "Email: $email<br>";
    echo "Clave: $clave<br>";
    echo "Operación en la petición: $operacion<br>";
}
fin_html();
?>
