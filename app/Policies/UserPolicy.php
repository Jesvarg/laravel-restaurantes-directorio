<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $currentUser, User $targetUser): bool
    {
        return $currentUser->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $currentUser, User $targetUser): bool
    {
        return $currentUser->role === 'admin' && $currentUser->id !== $targetUser->id;
    }

    public function delete(User $currentUser, User $targetUser): bool
    {
        return $currentUser->role === 'admin' && $currentUser->id !== $targetUser->id;
    }
}
