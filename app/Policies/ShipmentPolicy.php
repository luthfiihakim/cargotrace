<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Shipment $shipment): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Operator)
            || $shipment->customer_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Admin, UserRole::Operator);
    }

    public function update(User $user, Shipment $shipment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOperator() && ! $shipment->current_status->isTerminal();
    }

    public function delete(User $user, Shipment $shipment): bool
    {
        return $user->isAdmin();
    }
}
