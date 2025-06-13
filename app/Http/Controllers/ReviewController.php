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
    public function __construct()
    {
        // Asegurarse de que el usuario esté autenticado para todas las acciones de reseñas
        $this->middleware('auth');
    }

    /**
     * Mostrar el formulario para crear una nueva reseña para un restaurante específico.
     */
    public function create(Restaurant $restaurant)
    {
        return view('reviews.create', compact('restaurant'));
    }

    /**
     * Crear una nueva reseña.
     */
    public function store(StoreReviewRequest $request, Restaurant $restaurant)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $restaurant->reviews()->create($data);
            
            return redirect()->route('restaurants.show', $restaurant)
                ->with('success', 'Reseña publicada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al publicar la reseña: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar el formulario para editar una reseña.
     */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    /**
     * Actualizar una reseña.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        try {
            $review->update($request->validated());
            return redirect()->route('restaurants.show', $review->restaurant_id)
                ->with('success', 'Reseña actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al actualizar la reseña: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar una reseña.
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $restaurantId = $review->restaurant_id;

        try {
            $review->delete();
            return redirect()->route('restaurants.show', $restaurantId)
                ->with('success', 'Reseña eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la reseña: ' . $e->getMessage());
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