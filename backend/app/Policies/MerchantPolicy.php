<?php

namespace App\Policies;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MerchantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-merchants');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Merchant $merchant): bool
    {
        return $user->hasPermission('view-merchants');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-merchants');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Merchant $merchant): bool
    {
        return $user->hasPermission('update-merchants');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Merchant $merchant): bool
    {
        return $user->hasPermission('delete-merchants');
    }
}
