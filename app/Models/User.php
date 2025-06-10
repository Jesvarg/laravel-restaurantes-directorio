<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

   // Campos permitidos para asignación masiva
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

   /* protected $hidden = [
        'password',
        'remember_token',
    ];
    */

   // Campos que serán convertidos automáticamente
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones

    // Un usuario tiene muchos restaurantes
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    // Un usuario tiene muchas reseñas
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Un usuario puede tener varios restaurantes favoritos (relación many-to-many)
    public function favorites()
    {
        return $this->belongsToMany(Restaurant::class, 'favorites');
    }

    // Accessor: Capitalizar nombre
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
