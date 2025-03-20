<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Orders;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderDetailsController extends Controller
{
    public function show($reference_number)
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $order = Orders::where('reference_number', $reference_number)
            ->where('customer_id', $customer->id)
            ->with(['items.product'])
            ->firstOrFail();

        $orderData = [
            'id' => $order->id,
            'reference_number' => $order->reference_number,
            'status' => $order->order_status,
            'order_date' => $order->created_at->format('Y-m-d'),
            'payment_method' => $order->payment_method,
            'e_invoice' => [
                'number' => $order->e_invoice,
                'download_url' => $order->e_invoice ? route('invoice.download', $order->reference_number) : null,
            ],
            'shipping_fee' => $order->shipping_fee,
            'total_amount' => $order->total_amount,
            'subtotal' => $order->total_amount - $order->shipping_fee,
            'shipping_info' => [
                'name' => $order->customer->first_name . ' '. $order->customer->last_name,
                'address' => $order->shipping_address,
                'phone' => $order->customer->phone,
            ],
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'image' => $item->product->product_images[0] ?? null,
                    'sku' => $item->product->sku,
                    'category' => $item->product->category->name,
                    'brand' => $item->product->brand->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_amount,
                    'total' => $item->quantity * $item->unit_amount,
                ];
            }),
        ];

        return Inertia::render('ClientSide/Customer/OrderDetails', [
            'order' => $orderData,
            'customer' => $customer,
        ]);
    }

    public function downloadInvoice($reference_number)
    {
        try {
            $customer = Auth::guard('customer')->user();

            if (!$customer) {
                return redirect()->route('customer.login');
            }

            $order = Orders::where('reference_number', $reference_number)
                ->where('customer_id', $customer->id)
                ->firstOrFail();

            if (!$order->e_invoice) {
                // Generate invoice if it doesn't exist
                $order->load(['items.product', 'customer']);
                $pdf = PDF::loadView('invoices.template', ['order' => $order]);
                $filename = 'invoice-' . $order->reference_number . '.pdf';
                Storage::disk('public')->put('invoices/' . $filename, $pdf->output());
                $order->e_invoice = $filename;
                $order->save();
            }

            $path = storage_path('app/public/invoices/' . $order->e_invoice);

            if (!file_exists($path)) {
                Log::error('Invoice file not found: ' . $path);
                abort(404);
            }

            return response()->download($path);
        } catch (\Exception $e) {
            Log::error('Invoice download failed: ' . $e->getMessage());
            abort(500);
        }
    }
}
