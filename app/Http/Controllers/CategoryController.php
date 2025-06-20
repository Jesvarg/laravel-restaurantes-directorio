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
    public function __construct()
    {
        // Aplicar middleware de autorización a los métodos, excepto a los públicos
        // El middleware 'admin' ya protege las rutas, pero esto es una capa de seguridad adicional.
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Mostrar todas las categorías.
     */
    public function index()
    {
        $categories = Category::withCount('restaurants')->paginate(10);
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
            Category::create($request->validated());
            return redirect()->route('categories.index')
                ->with('success', 'Categoría creada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar una categoría específica y sus restaurantes.
     */
    public function show(Category $category)
    {
        $restaurants = $category->restaurants()->with(['user', 'photos', 'reviews'])->paginate(9);

        $favoriteRestaurantIds = [];
        if (auth()->check()) {
            $favoriteRestaurantIds = auth()->user()->favorites()->pluck('restaurant_id')->toArray();
        }

        return view('categories.show', compact('category', 'restaurants', 'favoriteRestaurantIds'));
    }

    /**
     * Mostrar el formulario para editar una categoría.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualizar una categoría.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            return redirect()->route('categories.index')
                ->with('success', 'Categoría actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            // Desasociar los restaurantes antes de eliminar la categoría
            $category->restaurants()->detach();
            $category->delete();
            DB::commit();
            
            return redirect()->route('categories.index')
                ->with('success', 'Categoría eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar restaurantes de una categoría específica.
     */
    public function restaurants(Category $category)
    {
        $restaurants = $category->restaurants()
            ->with(['reviews', 'photos', 'categories'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(12);
            
        $favoriteRestaurantIds = [];
        if (auth()->check()) {
            $favoriteRestaurantIds = auth()->user()->favoriteRestaurants()->pluck('restaurant_id')->toArray();
        }
        
        return view('categories.restaurants', compact('category', 'restaurants', 'favoriteRestaurantIds'));
    }
}

