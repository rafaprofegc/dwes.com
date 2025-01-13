<?php
namespace mvc\controlador;

use Exception;

class Controlador {
    protected string $peticion;
    protected string $vista_error = "mvc\\vista\\V_Error";

    public function gestiona_peticion() {

        try {
            // Obtener la petición
            $peticion = $_GET['idp'] ?? $_POST['idp'] ?? "Main";
            $this->peticion = filter_var($peticion, FILTER_SANITIZE_SPECIAL_CHARS);

            $clase_modelo = "mvc\\modelo\\M_" . ucfirst($this->peticion); 
            $clase_vista = "mvc\\vista\\V_" . ucfirst($this->peticion);

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
}
?>