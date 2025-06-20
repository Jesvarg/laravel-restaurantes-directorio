<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear el usuario Administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@restaurantes.com',
            'password' => Hash::make('Prueba123.'), // Cambia 'password' por una contraseña segura
            'role' => 'admin',
        ]);

        // Crear 10 usuarios normales
        User::factory(10)->create();

        // Crear categorías
        $categories = Category::factory(8)->create();

        // Crear 20 restaurantes y asignarle categorías
        Restaurant::factory(20)->create()->each(function ($restaurant) use ($categories) {
            // Asignar entre 1 y 3 categorías a cada restaurante
            $restaurant->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Crear entre 1 y 10 reseñas para cada restaurante
            Review::factory(rand(1, 10))->create([
                'restaurant_id' => $restaurant->id,
                'user_id' => User::where('role', 'user')->inRandomOrder()->first()->id, // Reseña de un usuario aleatorio
            ]);
        });
    }
}
