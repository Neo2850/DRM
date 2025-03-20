<?php

namespace App\Http\Controllers\Client;

use Inertia\Inertia;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [];

        if (Auth::guard('customer')->check()) {
            // Get cart items from database for authenticated users
            $cartItems = CartItem::where('customer_id', Auth::guard('customer')->id())
                ->with('product')
                ->get()
                ->map(function ($item) {
                    return [
                        $item->product_id,      // [0] id
                        $item->product->name,    // [1] name
                        $item->price,           // [2] price
                        $item->quantity,        // [3] quantity
                        $item->product->product_images[0] ?? null, // [4] image
                        $item->selected ?? true, // [5] selected - default to true if null
                    ];
                })
                ->keyBy(function ($item) {
                    return $item[0];
                })
                ->toArray();
        } else {
            // Get cart items from session for guests
            $cart = Session::get('cart', []);
            foreach ($cart as $productId => $item) {
                $cartItems[$productId] = [
                    $productId,           // [0] id
                    $item['name'],        // [1] name
                    $item['price'],       // [2] price
                    $item['quantity'],    // [3] quantity
                    $item['image'],       // [4] image
                    $item['selected'],    // [5] selected
                ];
            }
        }

        return Inertia::render('ClientSide/Cart', [
            'cart' => $cartItems
        ]);
    }

    public function addToCart(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            // For authenticated users, save to database
            $cartItem = CartItem::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                // If item exists, add the new quantity to the existing quantity
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $request->quantity
                ]);
            } else {
                // If item doesn't exist, create new
                CartItem::create([
                    'customer_id' => Auth::guard('customer')->id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $request->price,
                    'selected' => true
                ]);
            }
        } else {
            // For guests, save to session
            $cart = Session::get('cart', []);
            $productId = $request->product_id;

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $request->quantity;
            } else {
                $cart[$productId] = [
                    'name' => $request->name,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'image' => $request->image,
                    'selected' => true
                ];
            }

            Session::put('cart', $cart);
        }

        return back()->with('success', 'Product added to cart successfully');
    }

    public function updateQuantity(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            // For authenticated users, update database
            CartItem::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $request->product_id)
                ->update(['quantity' => $request->quantity]);
        } else {
            // For guests, update session
            $cart = Session::get('cart', []);
            $productId = $request->product_id;

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = $request->quantity;
                Session::put('cart', $cart);
            }
        }

        return back()->with('success', 'Quantity updated successfully');
    }

    public function removeFromCart(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            // For authenticated users, delete from database
            CartItem::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $request->product_id)
                ->delete();
        } else {
            // For guests, remove from session
            $cart = Session::get('cart', []);
            $productId = $request->product_id;

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                Session::put('cart', $cart);
            }
        }

        return back()->with('success', 'Product removed from cart successfully');
    }

    public function updateSelection(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            // For authenticated users, update database
            CartItem::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $request->product_id)
                ->update(['selected' => $request->selected]);
        } else {
            // For guests, update session
            $cart = Session::get('cart', []);
            $productId = $request->product_id;

            if (isset($cart[$productId])) {
                $cart[$productId]['selected'] = $request->selected;
                Session::put('cart', $cart);
            }
        }

        return back()->with('success', 'Selection updated successfully');
    }

    public function updateAllSelections(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            // For authenticated users, update all items in database
            CartItem::where('customer_id', Auth::guard('customer')->id())
                ->update(['selected' => $request->selected]);
        } else {
            // For guests, update all items in session
            $cart = Session::get('cart', []);

            foreach ($cart as $productId => $item) {
                $cart[$productId]['selected'] = $request->selected;
            }

            Session::put('cart', $cart);
        }

        return back()->with('success', 'All selections updated successfully');
    }

    public function clearCart()
    {
        if (Auth::guard('customer')->check()) {
            // For authenticated users, clear database items
            CartItem::where('customer_id', Auth::guard('customer')->id())->delete();
        } else {
            // For guests, clear session
            Session::forget('cart');
        }

        return back()->with('success', 'Cart cleared successfully');
    }

    public function addMultiple(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.image' => 'required|string'
        ]);

        if (Auth::guard('customer')->check()) {
            // For authenticated users, bulk insert to database
            foreach ($request->items as $item) {
                CartItem::updateOrCreate(
                    [
                        'customer_id' => Auth::guard('customer')->id(),
                        'product_id' => $item['product_id']
                    ],
                    [
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]
                );
            }
        } else {
            // For guests, add multiple items to session
            $cart = Session::get('cart', []);

            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $cart[$productId] = [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image'],
                    'selected' => true
                ];
            }

            Session::put('cart', $cart);
        }

        return back()->with('success', 'All items added to cart successfully');
    }
}
