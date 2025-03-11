<?php

namespace App\Http\Controllers;
use App\Models\Articulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\ArticuloRequest;
use App\Models\Categoria;

class ControladorArticulo extends Controller
{
    // Controlador artículos

    // Método para la ruta GET /articulos
    public function index(Request $request) {
        $articulos = Articulo::all();

        //$request->session()->put('clave','valor');
        //$articulos = Articulo::orderBy("descripcion")->take(10)->get();
        //$articulos = Articulo::where("descripcion", "like", "%kg%")->get();
        //$articulos = Articulo::where("pvp", "10")->get();
        return view("articulos.index", ["articulos" => $articulos, "numero" => $articulos->count()]);

    }

    // Método para la ruta GET /articulos/{ref}
    public function show($referencia) {
        // con find() no se dispara la excepción
        // $articulo = Articulo::find($referencia);

        try {
            
            $articulo = Articulo::findOrFail($referencia);
            return view("articulos.show", ['articulo' => $articulo]);
        }
        catch( ModelNotFoundException $mnfe) {
            return view("error", ['error' => ['mensaje' => "El artículo $referencia no existe",
                                              'enlace' => "/articulos",
                                              'texto' => "Regresar a la lista de artículos"]]);
        }
    }

    // Método para la ruta GET /articulos/create
    public function create() {
        // Presentar un formulario para crear un artículo

        $categorias = Categoria::all();
        return view("articulos.form", ['categorias' => $categorias,
                                       'titulo' => 'Crear un nuevo artículo',
                                       'op_crud' => 'create',
                                       'articulo' => null]);
    }

    public function store(ArticuloRequest $request) {
        
        $articulo = new Articulo();
        $articulo->referencia = $request->referencia;
        $articulo->descripcion = $request->descripcion;
        $articulo->pvp = $request->pvp;
        $dto = $request->dto_venta;
        if( $request->dto_venta && $request->dto_venta > 1 ) {
            $dto = $request->dto_venta / 100;
        }
        
        $articulo->dto_venta = $dto;
        $articulo->und_vendidas = 0;
        $articulo->und_disponibles = $request->und_disponibles;
        $articulo->fecha_disponible = $request->fecha_disponible;
        $articulo->categoria = $request->categoria;
        $articulo->tipo_iva = $request->tipo_iva;

        $articulo->save();

        // Puedo enviar los datos del artículo recién creado
        //return view("articulos.show", ['articulo' => $articulo]);

        // Puedo enviar la lista de artículos con un mensaje
        return redirect("/articulos")->with('resultado', "Artículo {$articulo->referencia} creado con éxito");
    }

    public function edit($referencia) {
        // Muestra un formulario con los datos del artículo a modificar
        $categorias = Categoria::all();
        $articulo = Articulo::find($referencia);

        /* Si la tabla tiene una clave primaria con dos columnas
            La petición REST para mostrar una dirección de envío
            GET /direcciones_envio/{nif}/{id_dir_env}
            GET /clientes/{nif}/direcciones_envio/{id_dir_env}

            La petición REST para modificar una dirección de envio
            PUT /direcciones_envio/{nif}/{id_dir_env}
            PUT /clientes/{nif}/direcciones_envio/{id_dir_env}

            $direccion_envio = DireccionEnvio::where("nif", $nif)->where("id_dir_env", $id_dir_env)->first();
        */

        if( $articulo ) {
            return view("articulos.form", ['categorias' => $categorias,
                                       'titulo' => 'Modificar un artículo',
                                       'op_crud' => 'edit',
                                       'articulo' => $articulo]);
        }
        else {
            return view("articulos.error", ['error' => ['mensaje' => "El artículo a modificar no existe"]]);
        }     

    }

    public function update( ArticuloRequest $request, string $referencia) {
        // Recibe los datos del formulario para actualizar un artículo

        $articulo = Articulo::find($referencia);

        $dto = $request->dto_venta;
        if( $dto > 1 ) {
            $dto = $dto / 100;
        }
        $request->dto_venta = $dto;

        $datos = $request->all();
        $datos['dto_venta'] = $dto;
        $articulo->update($datos);

        return redirect("/articulos")->with('resultado', "Artículo {$request->referencia} modificado con éxito");

        /*
        $articulo->referencia = $request->referencia;
        $articulo->descripcion = $request->descripcion;
        ...
        */



    }

    public function destroy($referencia) {

        // Elimina el artículo con clave $referencia
        // Articulo::destroy($referencia);

        $articulo = Articulo::find($referencia);
        if( $articulo ) {
            $articulo->delete();
            return redirect("/articulos")->with('resultado', "El artículo $referencia se ha borrado con éxito");
        }
    }


}
