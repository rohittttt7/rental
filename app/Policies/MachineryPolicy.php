<?php

namespace App\Policies;

use App\Models\Machinery;
use App\Models\User;

class MachineryPolicy
{
    /**
     * Determine whether the user can update the machinery.
     */
    public function update(User $user, Machinery $machinery): bool
    {
        return $user->id === $machinery->seller_id;
    }

    /**
     * Determine whether the user can delete the machinery.
     */
    public function delete(User $user, Machinery $machinery): bool
    {
        return $user->id === $machinery->seller_id;
    }
}