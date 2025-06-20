<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = ['name', 'address', 'phone', 'description', 'user_id'];

    // Fechas automáticas
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones

    // Un restaurante pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un restaurante tiene muchas reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un restaurante puede estar en varias categorías (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Un restaurante puede tener fotos polimórficas
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    // Scope: Filtrar restaurantes activos (si tienes campo status)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessor: Capitalizar nombre
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
	public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
