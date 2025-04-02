<?php
namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaControlador {
    public function index() {
        $categorias = Categoria::all();
        return view('categoria.index', compact('categorias'));
    }

    public function create() {
        $categorias = Categoria::all();
        return view('categoria.create', compact('categorias'));
    }

    public function store(Request $request) {
        $request->validate([
            'id_categoria' => 'required|string|max:4|unique:categoria,id_categoria',
            'descripcion' => 'required|string|max:50',
            'categoria_padre' => 'nullable|string|max:4|exists:categoria,id_categoria'
        ]);

        $categoria = Categoria::create($request->all());
        return view('categoria.store', compact('categoria'));
    }

    public function show($id) {
        try {
            $categoria = Categoria::findOrFail($id);
            return view('categoria.show', compact('categoria'));
        } catch (Exception $e) {
            return response()->view('categoria.error', ['error' => 'Categoría no encontrada'], 404);
        }
    }

    public function edit($id) {
        $categoria = Categoria::findOrFail($id);
        $categorias = Categoria::all();
        return view('categoria.edit', compact('categoria', 'categorias'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'descripcion' => 'required|string|max:50',
                'categoria_padre' => 'nullable|string|max:4|exists:categoria,id_categoria'
            ]);
            $categoria = Categoria::findOrFail($id);
            $categoria->update($request->all());
            

            return view('categoria.update', compact('categoria'));
        } catch (Exception $e) {
            return response()->view('categoria.error', ['error' => 'Categoría no encontrada'], 404);
        }
    }

    public function destroy($id) {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();
            return view('categoria.destroy', compact('categoria'));
            
        } catch (Exception $e) {
            return response()->view('categoria.error', ['error' => 'Categoría no encontrada'], 404);
        }

    }
}
