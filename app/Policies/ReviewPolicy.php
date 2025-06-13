<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si un usuario puede crear una reseña.
     * Cualquier usuario autenticado puede crear una reseña.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determina si el usuario puede actualizar la reseña.
     * Solo el autor de la reseña puede actualizarla.
     */
    public function update(User $user, Review $review)
    {
        return $user->id === $review->user_id;
    }

    /**
     * Determina si el usuario puede eliminar la reseña.
     * El autor de la reseña o un administrador pueden eliminarla.
     */
    public function delete(User $user, Review $review)
    {
        return $user->id === $review->user_id || $user->role === 'admin';
    }
}
