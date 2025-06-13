<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
        return response()->json($restaurants);
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
            
            // Cargar relaciones para la respuesta
            $restaurant->load('categories', 'photos');
            
            // Devolver respuesta JSON con código 201 (created)
            return response()->json([
                'success' => true,
                'data' => $restaurant
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            //  Manejo de errores
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el restaurante.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un restaurante específico.
     */
    public function show(Restaurant $restaurant)
    {
        // El restaurante ya se carga automáticamente gracias al Route Model Binding
        // Cargar relaciones adicionales
        $restaurant->load(['reviews.user', 'photos', 'categories', 'user']);

        return response()->json($restaurant);
    }

    /**
     * Actualizar un restaurante.
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        // Autorizar la acción usando la RestaurantPolicy
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
            
            DB::commit();
            
            $restaurant->load('categories', 'photos');
            
            return response()->json([
                'success' => true,
                'data' => $restaurant
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un restaurante.
     */
    public function destroy(Restaurant $restaurant)
    {
        // Autorizar la acción usando la RestaurantPolicy
        $this->authorize('delete', $restaurant);

        DB::beginTransaction();
        
        try {
            $restaurant->categories()->detach();
            $restaurant->photos()->delete();
            $restaurant->delete();
            
            DB::commit();
            return response()->json(['message' => 'Restaurante eliminado']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener los restaurantes favoritos de un usuario.
     */
    public function getUserFavorites(Request $request)
    {
        // Obtener favoritos del usuario autenticado
        $favorites = $request->user()->favorites()->with('categories', 'photos')->get();
        
        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }
    
    /**
     * Añadir un restaurante a favoritos.
     */
    public function addToFavorites(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id'
        ]);
        
        $user = $request->user();
        
        try {
            // Verificar si ya está en favoritos
            if ($user->favorites()->where('restaurant_id', $request->restaurant_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El restaurante ya está en favoritos'
                ], 422);
            }
            
            // Añadir a favoritos
            $user->favorites()->attach($request->restaurant_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Restaurante añadido a favoritos'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al añadir a favoritos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Eliminar un restaurante de favoritos.
     */
    public function removeFromFavorites(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id'
        ]);
        
        $user = $request->user();

        try {
            // Eliminar de favoritos
            $detached = $user->favorites()->detach($request->restaurant_id);

            if (!$detached) {
                return response()->json([
                    'success' => false,
                    'message' => 'El restaurante no estaba en favoritos'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Restaurante eliminado de favoritos'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar de favoritos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
