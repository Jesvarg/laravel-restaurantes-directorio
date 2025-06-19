<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = ['name'];

    // Relaciones

    // Una categoría puede estar asociada a muchos restaurantes (many-to-many)
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class);
    }

    // Accessor: Capitalizar nombre
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
