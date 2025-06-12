<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    /**
     * Mostrar todas las reseñas.
     */
    public function index()
    {
        $reviews = Review::with('user', 'restaurant')->paginate(20);
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Mostrar el formulario para crear una nueva reseña.
     */
    public function create(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $restaurant = null;
        
        if ($restaurantId) {
            $restaurant = Restaurant::find($restaurantId);
        }
        
        if (!$restaurant) {
            $restaurants = Restaurant::all();
            return view('reviews.create', compact('restaurants'));
        }
        
        return view('reviews.create', compact('restaurant'));
    }

    /**
     * Crear una nueva reseña.
     */
    public function store(StoreReviewRequest $request)
    {
        try {
            // Asignar el usuario actual como autor de la reseña
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $review = Review::create($data);
            
            return redirect()->route('restaurants.show', $review->restaurant_id)
                ->with('success', 'Reseña publicada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al publicar la reseña: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar una reseña específica.
     */
    public function show($id)
    {
        $review = Review::with('user', 'restaurant')->find($id);

        if (!$review) {
            return redirect()->route('reviews.index')
                ->with('error', 'Reseña no encontrada');
        }

        return view('reviews.show', compact('review'));
    }

    /**
     * Mostrar el formulario para editar una reseña.
     */
    public function edit($id)
    {
        $review = Review::find($id);
        
        if (!$review) {
            return redirect()->route('reviews.index')
                ->with('error', 'Reseña no encontrada');
        }
        
        // Verificar que el usuario actual es el autor de la reseña
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')
                ->with('error', 'No tienes permiso para editar esta reseña');
        }
        
        return view('reviews.edit', compact('review'));
    }

    /**
     * Actualizar una reseña.
     */
    public function update(UpdateReviewRequest $request, $id)
    {
        $review = Review::find($id);

        if (!$review) {
            return redirect()->route('reviews.index')
                ->with('error', 'Reseña no encontrada');
        }
        
        // Verificar que el usuario actual es el autor de la reseña
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')
                ->with('error', 'No tienes permiso para editar esta reseña');
        }

        try {
            $review->update($request->validated());
            return redirect()->route('restaurants.show', $review->restaurant_id)
                ->with('success', 'Reseña actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la reseña: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una reseña.
     */
    public function destroy($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return redirect()->route('reviews.index')
                ->with('error', 'Reseña no encontrada');
        }
        
        // Verificar que el usuario actual es el autor de la reseña o un administrador
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('reviews.index')
                ->with('error', 'No tienes permiso para eliminar esta reseña');
        }
        
        $restaurantId = $review->restaurant_id;

        try {
            $review->delete();
            return redirect()->route('restaurants.show', $restaurantId)
                ->with('success', 'Reseña eliminada correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la reseña: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar las reseñas del usuario actual.
     */
    public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('restaurant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('reviews.my-reviews', compact('reviews'));
    }
}