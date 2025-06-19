<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // Tabla explícita
    protected $table = 'favorites';

    // Campos permitidos para asignación masiva
    protected $fillable = ['user_id', 'restaurant_id'];
}
