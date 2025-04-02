<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorArticulo;
use App\Http\Controllers\ControladorClientes;

// Clases de las prácticas
use App\Http\Controllers\Controlador07;
use App\Http\Controllers\FormasEnvioControlador;
use App\Http\Controllers\ControladorAlumnos;
use App\Http\Controllers\ControladorReseña;
use App\Http\Controllers\DireccionEnvioControlador;
use App\Http\Controllers\PedidoControlador;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ClientesControlador;
use App\Http\Controllers\ControladorProveedor;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CategoriaControlador;

Route::get('/', function () {
    return view('welcome');
});

//Route::get("/articulos", [ControladorArticulo::class, "index"]);

/*
Route::get("/articulos/{ref}", function($ref) {
    return "Esto es para recuperar el artículo $ref";
})->whereNumber("ref");
*/
//Route::get("/articulos/{ref}", [ControladorArticulo::class, "show"]);


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
//Route::resource("/clientes", ControladorClientes::class);



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

// Controlador de recursos
Route::resource("/articulos", ControladorArticulo::class);
/*
// Jaime Grueso
Route::resource('/articulo_proveedor', Controlador07::class); 
Route::get('/articulo_proveedor/{referencia}/{nif}', [Controlador07::class, 'show']);
Route::get('/articulo_proveedor/{referencia}/{nif}/edit', [Controlador07::class, 'edit']);
Route::put('/articulo_proveedor/{referencia}/{nif}', [Controlador07::class, 'update']);
Route::any('/articulo_proveedoreseñasr/{referencia}/{nif}/delete', [Controlador07::class, 'destroy']);

// Alfonso Ramirez
Route::resource("/formasEnvio", FormasEnvioControlador::class);

// Adrián Velasco
Route::resource('alumnos', ControladorAlumnos::class);

// Salvador Vela
Route::resource("/reseñas", ControladorReseña::class);

// Daniel Bueno Vázquez
// LISTADO DE LOS RECURSOS
Route::get("/direccion-envio", [DireccionEnvioControlador::class, "index"])
->name("listarDirecciones");

// FORMULARIO CREAR RECURSO
Route::get("/direccion-envio/create", [DireccionEnvioControlador::class, "create"])
->name("crearDireccion");

// NUEVO REGISTRO EN BBDD
Route::post("/direccion-envio", [DireccionEnvioControlador::class, "store"])
->name("almacenarDireccion");

// DETALLE DE UN RECURSO
Route::get("/direccion-envio/{nif}/{id_dir_env}", [DireccionEnvioControlador::class, "show"])
->name("mostrarDireccion");

// FORMULARIO MODIFICAR RECURSO
Route::get("/direccion-envio/{nif}/{id_dir_env}/edit", [DireccionEnvioControlador::class, "edit"])
->name("editarDireccion");

// ACTUALIZAR RECURSO 
Route::put("/direccion-envio/{nif}/{id_dir_env}", [DireccionEnvioControlador::class, "update"])
->name("actualizarDireccion");
Route::patch("/direccion-envio/{nif}/{id_dir_env}", [DireccionEnvioControlador::class, "update"])
->name("actualizarDireccion");

// BORRAR UN RECURSO
Route::delete("/direccion-envio/{nif}/{id_dir_env}", [DireccionEnvioControlador::class, "destroy"])
->name("borrarDireccion");

// Alejandro Coronado
Route::resource('/pedidos', PedidoControlador::class);

// Alberto Luque
// Ruta principal que muestra la lista de envíos (INDEX)
Route::get('/recurso', [RecursoController::class, 'index'])->name('recurso.index');

// Ruta para mostrar el formulario de creación (CREATE)
Route::get('/recurso/create', [RecursoController::class, 'create'])->name('recurso.create');

// Ruta para almacenar un nuevo recurso (STORE)
Route::post('/recurso', [RecursoController::class, 'store'])->name('recurso.store');

// Ruta para mostrar los detalles de un envío específico (SHOW)
Route::get('/recurso/{id}', [RecursoController::class, 'show'])->name('recurso.show');

// Ruta para mostrar el formulario de edición (EDIT)
Route::get('/recurso/{id}/edit', [RecursoController::class, 'edit'])->name('recurso.edit');

// Ruta para actualizar un recurso específico (UPDATE)
Route::put('/recurso/{id}', [RecursoController::class, 'update'])->name('recurso.update');
Route::patch('/recurso/{id}', [RecursoController::class, 'update'])->name('recurso.update');

// Ruta para eliminar un recurso (DELETE)
Route::delete('/recurso/{id}', [RecursoController::class, 'destroy'])->name('recurso.destroy');

// Alberto Pérez Bernabeu
Route::resource('clientes', ClientesControlador::class);


// Alejandro Ruiz
Route::resource('/proveedor', ControladorProveedor::class);

// Manuel Jesús Ruiz
Route::resource("factura", FacturaController::class);
*/

// Salvador J. Martínez
Route::resource('/categoria', CategoriaControlador::class);