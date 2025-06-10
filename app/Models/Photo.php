<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    // Campos permitidos para asignación masiva
    protected $fillable = ['url', 'imageable_id', 'imageable_type'];

    // Relaciones

    // Polimorfismo: una foto puede pertenecer a cualquier modelo (por ejemplo, Restaurant)
    public function imageable()
    {
        return $this->morphTo();
    }
}

