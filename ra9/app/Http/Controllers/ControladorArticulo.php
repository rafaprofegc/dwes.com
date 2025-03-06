<?php

namespace App\Http\Controllers;
use App\Models\Articulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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

        $categorias = Categoria::all()->orderBy('descripcion');
        return view("articulos.create", ['categorias' => $categorias]);
    }


}
