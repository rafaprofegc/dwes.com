<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorArticulo;
use App\Http\Controllers\ControladorClientes;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/articulos", [ControladorArticulo::class, "index"]);

/*
Route::get("/articulos/{ref}", function($ref) {
    return "Esto es para recuperar el artículo $ref";
})->whereNumber("ref");
*/
Route::get("/articulos/{ref}", [ControladorArticulo::class, "show"]);


/* Gestión del recurso: clientes */
//Route::get("/clientes", [ControladorClientes::class, "index"]);
//Route::get("/clientes/{nif}", [ControladorClientes::class, "show"]);
// ... y el resto de rutas y métodos
// Más simple, usamos resource y automáticamente se asignan los métodos
// a cada ruta.
// Asigna las siguientes rutas a los siguientes métodos
/*
   Verbo    Ruta                     Método
   -------- ------------------------ ----------------
   GET      /clientes                index
   GET      /clientes/{nif}          show
   POST     /clientes                store
   GET      /clientes/create         create
   PUT      /clientes/{nif}          update
   PATH                                     
   GET      /clientes/{nif}/edit     edit
   DELETE   /clientes/{nif}          destroy

*/
Route::resource("/clientes", ControladorClientes::class);



Route::get("/clientes/{nif}", function($nif) {
    return "El nif del cliente es $nif";
})->where("nif", "[0-9]{8}[A-Z]");

Route::get("/clientes/{nif}/pedidos/{numero}", function($nif, $numero) {
    return "El nif de cliente es $nif y el número de pedido es $numero";
})->where("nif", "[0-9]{8}[A-Z]")->whereIn("numero", [1, 2, 3, 5]);

/*
Route::post();

Route::patch();

Route::put();

Route::delete();


Route::match(['get','post'], "/articulos", function() {
    return "La petición es get o post";
});
*/