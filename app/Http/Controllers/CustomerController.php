<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Orders;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::whereNotNull('email_verified_at')
            ->withCount('orders')
            ->paginate(10);
        return Inertia::render('AdminSide/Customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function showOrders($id)
    {
        $customer = Customer::with('orders')->findOrFail($id);

        return Inertia::render('AdminSide/Customers/CustomerOrders', [
            'customer' => [
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email' => $customer->email,
                'total_orders' => $customer->orders->count(),
                'completed_orders' => $customer->orders->where('order_status', 'delivered')->count(),
                'cancelled_orders' => $customer->orders->where('order_status', 'cancelled')->count(),
            ],
            'orders' => $customer->orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'reference_number' => $order->reference_number,
                    'created' => $order->created_at->format('m/d/Y'),
                    'total' => $order->total_amount,
                    'payment' => $order->payment_status,
                    'status' => $order->order_status,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'sku' => $item->product->sku ?? 'N/A',
                            'name' => $item->product->name ?? 'Unknown Product',
                            'price' => $item->unit_amount,
                            'quantity' => $item->quantity,
                            'total' => $item->unit_amount * $item->quantity,
                        ];
                    }),
                    'subtotal' => $order->items->sum(fn($item) => $item->unit_amount * $item->quantity),
                    'shipping' => $order->shipping_amount ?? 0,
                ];
            }),
        ]);
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,canceled,returned'
        ]);

        $order = Orders::findOrFail($orderId);
        $order->order_status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully');
    }
}
