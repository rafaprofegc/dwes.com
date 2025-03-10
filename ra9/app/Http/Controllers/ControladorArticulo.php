<?php

namespace App\Http\Controllers;
use App\Models\Articulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Categoria;

class ControladorArticulo extends Controller
{
    // Controlador artículos

    // Método para la ruta GET /articulos
    public function index() {
        $articulos = Articulo::all();
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
            return view("articulos.error", ['error' => ['mensaje' => "El artículo $referencia no existe"]]);
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

    public function store(Request $request) {
        $reglasValidacion = [
            'referencia'    => ['required', 'string', 'max:15', 'unique:articulo'],
            'descripcion'   => 'required|string|max:50',
            'pvp'           => 'required|numeric|min:0',
            'dto_venta'     => 'nullable|numeric|min:0|max:100',
            'und_disponibles' => 'nullable|numeric|min:0',
            //'fecha_disponible' => ['nullable', 'date', 'date_format:Y-m-d', Rule::date
            'fecha_disponible' => ['nullable', 'date', Rule::date('Y-m-d')->afterOrEqual(today())],
            'categoria'     => 'required|string|exists:categoria,id_categoria',
            'tipo_iva'      => ['nullable', 'string', Rule::in(['N','R','SR'])]
        ];

        $mensajesError = [ 
            'referencia.required'    => "La referencia es un dato obligatorio",
            'referencia.string'      => "La referencia es una cadena de caracteres",
            'referencia.unique'      => "La referencia ya existe en la tabla artículo",
            'referencia.max'         => "La referencia como máximo tiene 15 caracteres",
            'descripcion'            => "La descripción tiene que ser como máximo 50 caracteres",
            'pvp.min'                => "El PVP no puede ser negativo",
            'pvp.numeric'            => "El PVP tiene que ser numérico",
            'pvp.required'           => "El PVP es obligatorio",
            'dto_venta'              => "El descuento es un número entre 0 y 100",
            'und_disponibles'        => "Las unds disponibles es un número mayor que 0",
            'fecha_disponible'       => "La fecha disponible tiene que estar en el futuro",
            'categoria'              => "La categoria es obligatorio y tiene que existir",
            'tipo_iva'               => "El tipo de iva es N, R o SR"
        ];

        // Realizamos la validación
        $request->validate($reglasValidacion, $mensajesError );

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

    public function update( Request $request) {
        // Recibe los datos del formulario para actualizar un artículo
    }

    public function destroy($referencia) {

        // Elimina el artículo con clave $referencia
    }


}
