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
    public function index()
    {
        // Usar with() para cargar relaciones y evitar consultas múltiples
        $restaurants = Restaurant::with('user', 'categories', 'photos')->get();
        return view('restaurants.index', compact('restaurants'));
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
    public function show($id)
    {
        $restaurant = Restaurant::with(['reviews.user', 'photos', 'categories', 'user'])->find($id);

        if (!$restaurant) {
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurante no encontrado');
        }

        return view('restaurants.show', compact('restaurant'));
    }

    /**
     * Mostrar el formulario para editar un restaurante.
     */
    public function edit($id)
    {
        $restaurant = Restaurant::with('categories', 'photos')->find($id);
        
        if (!$restaurant) {
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurante no encontrado');
        }
        
        $categories = Category::all();
        
        return view('restaurants.edit', compact('restaurant', 'categories'));
    }

    /**
     * Actualizar un restaurante.
     */
    public function update(UpdateRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurante no encontrado');
        }

        DB::beginTransaction();
        
        try {
            // Actualizar datos básicos del restaurante
            $restaurant->update($request->validated());
            
            // Actualizar categorías si se proporcionan
            if ($request->has('categories')) {
                // sync reemplaza todas las categorías existentes con las nuevas
                $restaurant->categories()->sync($request->categories);
            }
            
            // Procesar nuevas fotos si se proporcionan
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
            
            // Eliminar fotos si se especifica
            if ($request->has('photos_to_delete') && is_array($request->photos_to_delete)) {
                $restaurant->photos()->whereIn('id', $request->photos_to_delete)->delete();
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
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return redirect()->route('restaurants.index')
                ->with('error', 'Restaurante no encontrado');
        }

        DB::beginTransaction();
        
        try {
            // Eliminar relaciones manualmente si es necesario
            // (aunque las migraciones ya tienen onDelete('cascade'))
            $restaurant->categories()->detach();
            $restaurant->photos()->delete();
            
            // Eliminar el restaurante
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
    public function addToFavorites($id)
    {
        $restaurant = Restaurant::find($id);
        
        if (!$restaurant) {
            return redirect()->back()->with('error', 'Restaurante no encontrado');
        }
        
        $user = Auth::user();
        
        // Verificar si ya está en favoritos
        $exists = Favorite::where('user_id', $user->id)
            ->where('restaurant_id', $id)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('info', 'El restaurante ya está en favoritos');
        }
        
        // Añadir a favoritos
        Favorite::create([
            'user_id' => $user->id,
            'restaurant_id' => $id
        ]);
        
        return redirect()->back()->with('success', 'Restaurante añadido a favoritos');
    }
    
    /**
     * Eliminar un restaurante de favoritos.
     */
    public function removeFromFavorites($id)
    {
        $user = Auth::user();
        
        $deleted = Favorite::where('user_id', $user->id)
            ->where('restaurant_id', $id)
            ->delete();
            
        if (!$deleted) {
            return redirect()->back()->with('error', 'El restaurante no estaba en favoritos');
        }
        
        return redirect()->back()->with('success', 'Restaurante eliminado de favoritos');
    }
}