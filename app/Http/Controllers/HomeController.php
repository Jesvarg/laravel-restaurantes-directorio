<?php
namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la página de inicio.
     */
    public function index()
    {
        // Obtener restaurantes destacados (los mejor valorados)
        $restaurants = Restaurant::with(['reviews', 'categories', 'photos'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(6)
            ->get();
            
        // Obtener categorías populares
        $categories = Category::withCount('restaurants')
            ->orderByDesc('restaurants_count')
            ->take(6)
            ->get();
            
        // Estadísticas generales
        $stats = [
            'restaurants' => Restaurant::count(),
            'categories' => Category::count(),
            'reviews' => Review::count(),
            'users' => User::count()
        ];
        
        return view('home', compact('restaurants', 'categories', 'stats'));
    }
}
