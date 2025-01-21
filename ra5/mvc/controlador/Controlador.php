<?php
namespace mvc\controlador;

use Exception;
use util\seguridad\JWT;

class Controlador {
    protected string $peticion;
    protected string $vista_error = "mvc\\vista\\V_Error";

    protected array $peticiones;

    public function __construct() {
        $this->peticiones = ['Main' => ['modelo' => "mvc\\modelo\\M_Main",
                                        'vista' => "mvc\\vista\\V_Main"],
                             'articulos' => ['modelo' => "mvc\\modelo\\M_Articulos",
                                             'vista'=> "mvc\\vista\\V_Articulos"],
                             'rc001' => ['modelo'   => "mvc\\modelo\\M_Registro_cliente",
                                         'vista'    => "mvc\\vista\\V_Registro_cliente"],
                             'autenticar' => ['modelo' => "mvc\\modelo\\M_Autenticar",
                                              'vista' => "mvc\\vista\\V_Autenticar"],
                             'buscar' => ['modelo' => "mvc\\modelo\\M_Buscar",
                                          'vista'=> "mvc\\vista\\V_Buscar",
                                          'metodo' => 'POST'],
                             'añadir' => ['modelo' => "mvc\\modelo\M_Añadir",
                                          'vista'  => "mvc\\vista\\V_Añadir"],
                             'finalizar_compra' => ['modelo' => "mvc\\modelo\\M_Finalizar_compra",
                                                    'vista'  => "mvc\\vista\\V_Finalizar_compra"],
                             'crear_pedido'     => ['modelo' => "mvc\\modelo\\M_Crear_pedido",
                                                    'vista'  => "mvc\\vista\\V_Crear_pedido"]          
    ];
    }

    public function gestiona_peticion() {

        try {
            
            $this->verifica_usuario();

            // Obtener la petición
            $peticion = $_GET['idp'] ?? $_POST['idp'] ?? "Main";
            $this->peticion = filter_var($peticion, FILTER_SANITIZE_SPECIAL_CHARS);

            $clase_modelo = "mvc\\modelo\\M_" . ucfirst($this->peticion); 
            $clase_vista = "mvc\\vista\\V_" . ucfirst($this->peticion);

            // Si usamos un array para las peticiones
            if( array_key_exists($peticion, $this->peticiones ) ) {
                $clase_modelo = $this->peticiones[$peticion]['modelo'];
                $clase_vista = $this->peticiones[$peticion]['vista'];
            }
            // Gestión de error si el modelo o la vista no existen
            
            if( !class_exists($clase_modelo) ) {
                throw new Exception("La clase modelo $clase_modelo no existe", 1);
            }

            if( !class_exists($clase_vista) ) {
                throw new Exception("La clase vista $clase_vista no existe", 2);
            }
            

            // Instanciar las clases modelo y vista
            $modelo = new $clase_modelo();
            $datos = $modelo->despacha();

            $vista = new $clase_vista();
            $vista->genera_salida($datos);

        }
        catch(Exception $e) {
            $vista_error = new $this->vista_error();
            $vista_error->genera_salida($e);
        }
    }

    private function verifica_usuario(): void {
        // Identificar al usuario
        if( isset($_COOKIE['jwt']) ) {
            $jwt = $_COOKIE['jwt'];
            $payload = JWT::verificar_token($jwt);
            if( !$payload ) {
                throw new Exception("El token no ha pasado la verificación", 4005);
            }
        }
        else {
            if( isset($_SESSION['cliente']) ) {
                unset($_SESSION['cliente']);
                unset($_SESSION['carrito']);
            }
        }

    }
}
?>