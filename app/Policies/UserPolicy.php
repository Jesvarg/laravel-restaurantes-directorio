<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver la lista de usuarios.
     * Solo los administradores pueden ver la lista de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determina si el usuario puede actualizar el rol de otro usuario.
     * Solo los administradores pueden hacerlo, y no pueden cambiar su propio rol.
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        return $currentUser->role === 'admin' && $currentUser->id !== $targetUser->id;
    }
}
