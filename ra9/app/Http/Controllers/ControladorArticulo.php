<?php

namespace App\Http\Controllers;
use App\Models\Articulo;

use Illuminate\Http\Request;

class ControladorArticulo extends Controller
{
    // Controlador artículos

    // Método para la ruta /articulos
    public function index() {
        $articulos = Articulo::all();
        $articulos = Articulo::orderBy("descripcion")->take(10)->get();
        $articulos = Articulo::where("descripcion", "like", "%kg%")->get();
        $articulos = Articulo::where("pvp", "10")->get();
        return view("articulos", ["articulos" => $articulos]);

    }

    public function show($referencia) {
        $articulo = Articulo::find($referencia);
        return $articulo;
    }
}
