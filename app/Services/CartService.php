<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function migrateGuestCartToUser($customerId)
    {
        $sessionCart = Session::get('cart', []);

        foreach ($sessionCart as $productId => $item) {
            CartItem::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    'product_id' => $productId
                ],
                [
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]
            );
        }

        // Clear the session cart after migration
        Session::forget('cart');
    }

    public function getCartCount()
    {
        if (Auth::guard('customer')->check()) {
            return CartItem::where('customer_id', Auth::guard('customer')->id())->sum('quantity');
        } else {
            $cart = Session::get('cart', []);
            return collect($cart)->sum('quantity');
        }
    }
}
