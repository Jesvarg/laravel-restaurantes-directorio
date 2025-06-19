<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;

class RestaurantController extends Controller
{
    /**
     * Mostrar todos los restaurantes.
     */
    public function index(Request $request)
    {
        $query = Restaurant::with(['user', 'categories', 'photos', 'reviews']);

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $restaurants = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Obtener los IDs de los restaurantes favoritos del usuario para evitar N+1 en la vista
        $favoriteRestaurantIds = auth()->check() 
            ? auth()->user()->favorites()->pluck('restaurants.id')->toArray()
            : [];

        return view('restaurants.index', compact('restaurants', 'categories', 'favoriteRestaurantIds'));
    }

    /**
     * Mostrar el formulario para crear un nuevo restaurante.
     */
    public function create()
    {
        $categories = Category::all();
        return view('restaurants.create', compact('categories'));
    }

    /**
     * Crear un nuevo restaurante.
     */
    public function store(StoreRestaurantRequest $request)
    {
        // Usar transacción para garantizar integridad de datos
        DB::beginTransaction();
        
        try {
            // Crear el restaurante
            $restaurant = Restaurant::create($request->validated());
            
            // Asociar categorías si se proporcionan
            if ($request->has('categories')) {
                $restaurant->categories()->attach($request->categories);
            }
            
            // Procesar fotos si se proporcionan
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    // Guardar la imagen y obtener la URL
                    $path = $photo->store('restaurants', 'public');
                    $url = asset('storage/' . $path);
                    
                    // Crear la foto asociada al restaurante
                    $restaurant->photos()->create([
                        'url' => $url
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('restaurants.show', $restaurant->id)
                ->with('success', 'Restaurante creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el restaurante: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar un restaurante específico.
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['reviews.user', 'photos', 'categories', 'user']);
        return view('restaurants.show', compact('restaurant'));
    }

    /**
     * Mostrar el formulario para editar un restaurante.
     */
    public function edit(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $categories = Category::all();
        $restaurant->load('categories', 'photos');

        return view('restaurants.edit', compact('restaurant', 'categories'));
    }

    /**
     * Actualizar un restaurante.
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        DB::beginTransaction();
        
        try {
            $restaurant->update($request->validated());
            
            if ($request->has('categories')) {
                $restaurant->categories()->sync($request->categories);
            }
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('restaurants', 'public');
                    $url = asset('storage/' . $path);
                    $restaurant->photos()->create(['url' => $url]);
                }
            }
            
            if ($request->has('photos_to_delete') && is_array($request->photos_to_delete)) {
                Photo::whereIn('id', $request->photos_to_delete)->delete();
            }
            
            DB::commit();
            
            return redirect()->route('restaurants.show', $restaurant->id)
                ->with('success', 'Restaurante actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el restaurante: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar un restaurante.
     */
    public function destroy(Restaurant $restaurant)
    {
        $this->authorize('delete', $restaurant);

        DB::beginTransaction();
        
        try {
            $restaurant->categories()->detach();
            $restaurant->photos()->delete();
            $restaurant->delete();
            
            DB::commit();
            return redirect()->route('restaurants.index')
                ->with('success', 'Restaurante eliminado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar el restaurante: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar los restaurantes favoritos del usuario autenticado.
     */
    public function favorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('categories', 'photos')->get();
        
        return view('restaurants.favorites', compact('favorites'));
    }
    
    /**
     * Añadir un restaurante a favoritos.
     */
    public function addToFavorites(Restaurant $restaurant)
    {
        $user = Auth::user();
        
        if ($user->favorites()->where('restaurant_id', $restaurant->id)->exists()) {
            return redirect()->back()->with('info', 'El restaurante ya está en favoritos');
        }
        
        $user->favorites()->attach($restaurant->id);
        
        return redirect()->back()->with('success', 'Restaurante añadido a favoritos');
    }
    
    /**
     * Eliminar un restaurante de favoritos.
     */
    public function removeFromFavorites(Request $request, Restaurant $restaurant)
    {
        auth()->user()->favorites()->detach($restaurant->id);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Restaurante eliminado de favoritos.'
            ]);
        }

        return back()->with('success', 'Restaurante eliminado de favoritos.');
    }
}