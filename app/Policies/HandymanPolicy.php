<?php

namespace App\Policies;

use App\Models\Handyman;
use App\Models\User;
use Illuminate\Auth\Access\Response;    

class HandymanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Handyman $user, Handyman $model)
    {
        return  true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Handyman $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Handyman $user, Handyman $model): bool
    {
        return $user->id ===  $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Handyman $user, Handyman $model): bool
    {
        return $user->id === $model->user;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Handyman $user, Handyman $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Handyman $user, Handyman $model): bool
    {
        //
    }
}
