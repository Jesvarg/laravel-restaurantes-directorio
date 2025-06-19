<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier categoría.
     */
    public function viewAny(User $user)
    {
        return true; // Cualquiera puede ver el listado
    }

    /**
     * Determina si el usuario puede ver una categoría específica.
     */
    public function view(User $user, Category $category)
    {
        return true; // Cualquiera puede ver una categoría
    }

    /**
     * Determina si el usuario puede crear categorías.
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determina si el usuario puede actualizar una categoría.
     */
    public function update(User $user, Category $category)
    {
        return $user->role === 'admin';
    }

    /**
     * Determina si el usuario puede eliminar una categoría.
     */
    public function delete(User $user, Category $category)
    {
        return $user->role === 'admin';
    }
}
