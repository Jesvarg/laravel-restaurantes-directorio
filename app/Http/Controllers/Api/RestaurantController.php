<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

        //  Crear registro
        try {
            $restaurant = Restaurant::create($request->validated());
            // Devolver respuesta JSON con código 201 (created)
            return response()->json([
                'success' => true,
                'data' => $restaurant
            ], 201);
        } catch (\Exception $e) {
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
    public function show($id)
    {
        $restaurant = Restaurant::with('reviews', 'photos')->find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        }

        return response()->json($restaurant);
    }

    /**
     * Actualizar un restaurante.
     */
    public function update(UpdateRestaurantRequest $request, $id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        }

        try {
            $restaurant->update($request->validated());
            return response()->json([
                'success' => true,
                'data' => $restaurant
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un restaurante.
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurante no encontrado'], 404);
        }

        try {
            $restaurant->delete();
            return response()->json(['message' => 'Restaurante eliminado']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
