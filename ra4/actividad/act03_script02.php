<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/autenticacion/03jwt_include.php");
require_once("act03_include.php");

$ing_veg = [
    ['nombre' => 'Pepino', 'precio' => 1],
    ['nombre' => 'Calabacín', 'precio' => 1.5],
    ['nombre' => 'Pimiento verde', 'precio' => 1.25],
    ['nombre' => 'Pimiento rojo', 'precio' => 1.75],
    ['nombre' => 'Tomate', 'precio' => 1.5],
    ['nombre' => 'Aceitunas', 'precio' => 3],
    ['nombre' => 'Cebolla', 'precio' => 1]
];

$ing_no_veg = [
    ['nombre' => 'Atún', 'precio' => 2],
    ['nombre' => 'Carne picada', 'precio' => 2.5],
    ['nombre' => 'Peperoni', 'precio' => 1.75],
    ['nombre' => 'Morcilla', 'precio' => 2.25],
    ['nombre' => 'Anchoas', 'precio' => 1.5],
    ['nombre' => 'Salmón', 'precio' => 3],
    ['nombre' => 'Gambas', 'precio' => 4],
    ['nombre' => 'Langostinos', 'precio' => 4]
];

function autenticar($usuario, $clave) {
    $usuarios = ['pepe' => ['nombre' => 'José Gómez', 
                            'clave' => password_hash("usuario", PASSWORD_DEFAULT)],
                 'manolo' => ['nombre' => 'Manuel García',
                              'clave' => password_hash("usuario", PASSWORD_DEFAULT)]
    ];

    if( array_key_exists($usuario, $usuarios) ) {
        return password_verify($clave, $usuarios[$usuario]['clave']);
    }
    else {
        return false;
    }                           
}

if( $_SERVER['REQUEST_METHOD'] == "GET") {
    header("Location: /ra4/actividad/act03_script01.php");
}

if( $_SERVER['REQUEST_METHOD'] == "POST" && $_POST['operacion'] == "Añadir ingredientes") {

    // Recoger los datos
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);

    $clave = $_POST['clave'];

    if( !autenticar($nombre, $clave) ) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(1);
    }

    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_SPECIAL_CHARS);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);
    $telefono = filter_var($telefono, FILTER_VALIDATE_INT);

    if( !$telefono ) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(2);
    }

    $usuario = ['nombre'    => $nombre,
                'telefono'  => $telefono,
                'direccion' => $direccion ];

    $jwt = generar_token($usuario);

    setcookie("token", $jwt, time() + 30 * 60);

    $_SESSION['ingredientes'] = [];
    $_SESSION['inicio'] = time();
    
    $vegetariana = filter_input(INPUT_POST, 'vegetariana', FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    $ingredientes_disponibles = $vegetariana ? $ing_veg : $ing_no_veg;
    $_SESSION['ingredientes_disponibles'] = $ingredientes_disponibles;
    $_SESSION['vegetariana'] = $vegetariana;

    inicio_html("Pizzas por encargo", ["/estilos/general.css","/estilos/formulario.css", "/estilos/tablas.css"]);
    echo "<header>Pizzas por encargo</header>";
}
elseif( $_SERVER['REQUEST_METHOD'] == "POST" && $_POST['operacion'] == "Otro Ingrediente") {
    if( !isset($_COOKIE['token'])) {
        header("Location: /ra4/actividad/act03_script01.php");
        exit(3);
    }

    $jwt = $_COOKIE['token'];
    $payload = verificar_token($jwt);
    if( !$payload ) {
        header("Location: /ra4/actividad/act03_script01.php");
    }

    // El token está verificado y es correcto
    // Recogemos los datos del ingrediente
    if( isset($_SESSION['ingredientes_disponibles']) && 
        isset($_SESSION['ingredientes']) && isset($_SESSION['vegetariana']) ) {
        $ingredientes_disponibles = $_SESSION['ingredientes_disponibles'];
        $vegetariana = $_SESSION['vegetariana'];
    }
    else
        header("Location: /ra4/actividad/act03_script01.php");

    $ingrediente = filter_input(INPUT_POST, 'ingrediente', FILTER_SANITIZE_NUMBER_INT);
    $ingrediente = filter_var($ingrediente, FILTER_VALIDATE_INT);
    if( array_key_exists($ingrediente, $ingredientes_disponibles) ) {
        if( !in_array($ingredientes_disponibles[$ingrediente], $_SESSION['ingredientes']) ) {
            $_SESSION['ingredientes'][] = $ingredientes_disponibles[$ingrediente];
        }
    }

    inicio_html("Pizzas por encargo", ["/estilos/general.css","/estilos/formulario.css", "/estilos/tablas.css"]);
    echo "<header>Pizzas por encargo</header>";
    MuestraDatos($payload, $_SESSION['ingredientes'], $vegetariana);
}
?>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <fieldset>
            <legend>Introduce un ingrediente</legend>
            <label for="ingrediente">Ingrediente</label>
            <select name="ingrediente" id="ingrediente" size="1">
<?php
            foreach($ingredientes_disponibles as $clave => $ingrediente) {
                echo "<option value='$clave'>{$ingrediente['nombre']} - {$ingrediente['precio']}€</option>";
            }
?>
            </select>
        </fieldset>
        <input type="submit" name="operacion" id="operacion" value="Otro Ingrediente">
    </form>

    <form action="act03_script03.php" method="POST">
        <input type="submit" name="operacion" id="operacion" value="Ir a los extras">
    </form>
<?php
fin_html();
?>