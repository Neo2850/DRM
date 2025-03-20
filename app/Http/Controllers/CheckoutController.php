<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Orders;
use App\Models\CartItem;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use App\Models\CustomerAddresses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // Check if user has any selected items in cart
        $hasItems = CartItem::where('customer_id', Auth::guard('customer')->id())
            ->where('selected', true)
            ->exists();

        if (!$hasItems) {
            return redirect()->route('cart')->with('error', 'Your cart is empty. Please add items before proceeding to checkout.');
        }

        // Get authenticated customer
        $customer = Auth::guard('customer')->user();
        $address = CustomerAddresses::where('customer_id', $customer->id)->first();

        // Get selected cart items
        $selectedItems = CartItem::where('customer_id', $customer->id)
            ->where('selected', true)
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->product_images[0] ?? null,
                    'subtotal' => $item->price * $item->quantity
                ];
            });

        // Calculate totals
        $subtotal = $selectedItems->sum('subtotal');
        $shipping = request('delivery_method') === 'pickup' ? 0 : 145;
        $total = $subtotal + $shipping;

        return Inertia::render('ClientSide/Customer/Checkout', [
            'customer' => [
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $address,
            ],
            'items' => $selectedItems,
            'summary' => [
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total
            ],
        ]);
    }

    public function pay(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $address = CustomerAddresses::where('customer_id', $customer->id)->first();

        if (!$address) {
            return back()->withErrors(['error' => 'Please add a delivery address first.']);
        }

        // Get selected cart items
        $selectedItems = CartItem::where('customer_id', $customer->id)
            ->where('selected', true)
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->product_images[0] ?? null,
                    'subtotal' => $item->price * $item->quantity
                ];
            });

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['error' => 'No items selected for checkout.']);
        }

        // Calculate totals
        $subtotal = $selectedItems->sum('subtotal');
        $shipping = request('delivery_method') === 'pickup' ? 0 : 145;
        $total = $subtotal + $shipping;

        // Create Paymongo source
        $data = [
            'data' => [
                'attributes' => [
                    'amount' => $total * 100, // Convert to cents
                    'currency' => 'PHP',
                    'type' => 'gcash',
                    'redirect' => [
                        'success' => route('customer.payment.success'),
                        'failed' => route('customer.checkout'),
                    ],
                    'billing' => [
                        'name' => $customer->first_name . ' ' . $customer->last_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'address' => [
                            'line1' => $address->complete_address,
                            'city' => $address->city,
                            'state' => $address->state,
                            'postal_code' => $address->zip_code,
                            'country' => 'PH'
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = Curl::to('https://api.paymongo.com/v1/sources')
                ->withHeader('Content-Type: application/json')
                ->withHeader('Accept: application/json')
                ->withHeader('Authorization: Basic ' . base64_encode(env('AUTH_PAY') . ':'))
                ->withData($data)
                ->asJson()
                ->post();

            if (!$response || isset($response->errors)) {
                throw new \Exception('Failed to create payment source');
            }

            // Store payment data in session with the notes and shipping address from request
            Session::put('payment_data', [
                'response_id' => $response->data->id,
                'selected_items' => $selectedItems,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => 'gcash',
                'customer' => $customer,
                'notes' => $request->input('notes'),  // Get notes from request
                'shipping_address' => $request->input('shipping_address')  // Get shipping address from request
            ]);

            // Return the checkout URL in an Inertia response
            return Inertia::location($response->data->attributes->redirect->checkout_url);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment initialization failed. Please try again.']);
        }
    }

    public function success()
    {
        $paymentData = Session::get('payment_data');

        if (!$paymentData) {
            return redirect()->route('customer.checkout')->withErrors(['error' => 'Payment session expired.']);
        }

        // Create the order with shipping address and notes
        $order = Orders::create([
            'reference_number' => 'ORD-' . uniqid(),
            'customer_id' => $paymentData['customer']->id,
            'total_amount' => $paymentData['total'],
            'payment_method' => $paymentData['payment_method'],
            'shipping_method' => 'delivery',
            'shipping_amount' => $paymentData['shipping'],
            'status' => 'pending',
            'payment_status' => 'paid',
            'notes' => $paymentData['notes'] ?? null,
            'shipping_address' => $paymentData['shipping_address']
        ]);

        // Create order items
        foreach ($paymentData['selected_items'] as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item['price'],
                'total_amount' => $item['subtotal']
            ]);
        }

        // Clear selected cart items
        CartItem::where('customer_id', $paymentData['customer']->id)
            ->where('selected', true)
            ->delete();

        Session::forget('payment_data');

        return redirect()->route('customer.myOrders')->with('success', 'Payment successful! Your order has been placed.');
    }
}
