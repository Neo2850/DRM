<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Orders;
use App\Models\OrderItems; // Corrected import
use Illuminate\Support\Facades\Auth;

class MyOrdersController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $orders = Orders::where('customer_id', $customer->id)
            ->with(['items.product']) // Proper eager loading
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'reference_number' => $order->reference_number, 
                    'status' => $order->order_status,
                    'order_date' => $order->created_at->format('Y-m-d'),
                    'total_amount' => $order->total_amount,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_name' => $item->product->name ?? 'Unknown Product',
                            'category_id'  => $item->product->category_id ?? 'Unknown Category',
                            'brand_id' => $item->product->brand_id ?? 'Unknown Brand',
                            'product_image' => $item->product->product_images[0] ?? null,
                            'sku' => $item->product->sku ?? 'N/A',
                            'quantity' => $item->quantity,
                            'unit_amount' => $item->unit_amount,
                        ];
                    }),
                ];
            });

        return Inertia::render('ClientSide/Customer/MyOrders', [
            'orders' => $orders,
            'customer' => $customer,
        ]);
    }
}
