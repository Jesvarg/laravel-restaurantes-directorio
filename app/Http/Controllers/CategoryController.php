<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Mostrar todas las categorías.
     */
    public function index()
    {
        $categories = Category::withCount('restaurants')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Mostrar el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Crear una nueva categoría.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());
            return redirect()->route('categories.show', $category->id)
                ->with('success', 'Categoría creada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar una categoría específica.
     */
    public function show($id)
    {
        $category = Category::with('restaurants')->find($id);

        if (!$category) {
            return redirect()->route('categories.index')
                ->with('error', 'Categoría no encontrada');
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Mostrar el formulario para editar una categoría.
     */
    public function edit($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return redirect()->route('categories.index')
                ->with('error', 'Categoría no encontrada');
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualizar una categoría.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('categories.index')
                ->with('error', 'Categoría no encontrada');
        }

        try {
            $category->update($request->validated());
            return redirect()->route('categories.show', $category->id)
                ->with('success', 'Categoría actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('categories.index')
                ->with('error', 'Categoría no encontrada');
        }

        DB::beginTransaction();
        
        try {
            // Verificar si hay restaurantes asociados
            $restaurantsCount = $category->restaurants()->count();
            
            if ($restaurantsCount > 0) {
                // Desasociar los restaurantes en lugar de impedir la eliminación
                $category->restaurants()->detach();
            }
            
            $category->delete();
            
            DB::commit();
            
            return redirect()->route('categories.index')
                ->with('success', 'Categoría eliminada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar restaurantes por categoría.
     */
    public function restaurants($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return redirect()->route('categories.index')
                ->with('error', 'Categoría no encontrada');
        }
        
        $restaurants = $category->restaurants()->with('photos', 'user')->paginate(10);
        
        return view('categories.restaurants', compact('category', 'restaurants'));
    }
}