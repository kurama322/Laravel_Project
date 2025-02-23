<?php

namespace App\Listeners\Cart;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Logout;

class RestoreOnLoginListener
{

    public function handle(Logout $event): void
    {
        Cart::instance('cart')->restore('cart_' .  $event->user->id);
    }
}
