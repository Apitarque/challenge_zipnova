<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, Order $order): bool
    {
        if (!$order->user) {
            return false;
        }

        return $user->id === $order->user->id;
    }
}
