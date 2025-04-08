<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']. "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");
require_once("include00.php");


function presentar_formulario($cursos) {
    
?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
        <legend>Matrícula de curso</legend>
        <label for="curso">Curso</label>
        <select name="curso" id="curso" size="1">
<?php
        foreach($cursos as $curso => $datos) {
            echo "<option value='$curso'>{$datos['descripcion']}</option>";
        }
?>
        </select>

        <label for="horas">Horas</label>
        <input type="text" name="horas" id="horas" size="3" required>

        <label></label>
        <input type="submit" name="operacion" id="operacion" value="Registra curso">

    </fieldset>
</form>
<?php
}

function registrar_curso($cursos) {
    $curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_SPECIAL_CHARS);
    $horas = filter_input(INPUT_POST, 'horas', FILTER_SANITIZE_SPECIAL_CHARS);
    $horas = filter_var($horas, FILTER_VALIDATE_INT);

    if( !array_key_exists($curso, $cursos)) {

    }

    $_SESSION['cursos'][$curso] = [$cursos[$curso]['descripcion'],
                                   $cursos[$curso]['precio'], $horas ];




}


// Verificar el token
if( isset($_COOKIE['jwt'])) {
    $jwt = $_COOKIE['jwt'];
    $payload = verificar_token($jwt);
    if(  $payload ) {
        presentar_datos($payload);
    }
    else {
        $_SESSION['error'] = "No se ha verificado el token de autenticación.";
        header("Location: index00.php");
    }
}
else {
    $_SESSION['error'] = "No se ha verificado el token de autenticación.";
    header("Location: index00.php");
}

if( $_SERVER['REQUEST_METHOD'] == "POST") {
    registrar_curso($cursos);
}

// Presentar el formulario
inicio_html("Recuperación RA4 - Lista de cursos", ["/estilos/general.css", "/estilos/formulario.css"]);
presentar_formulario($cursos);
fin_html();
?>