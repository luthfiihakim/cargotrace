<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Carrier;
use App\Models\User;

class CarrierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Operator);
    }

    public function view(User $user, Carrier $carrier): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Operator);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Carrier $carrier): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Carrier $carrier): bool
    {
        return $user->isAdmin();
    }
}
