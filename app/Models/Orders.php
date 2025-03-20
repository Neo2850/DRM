<?php

namespace App\Models;

use App\Models\OrderItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Orders extends Model
{
    protected $fillable = [
        'reference_number',
        'customer_id',
        'total_amount',
        'payment_status',
        'payment_method',
        'shipping_method',
        'tracking_number',
        'estimated_delivery',
        'pickup_date_time',
        'shipping_amount',
        'order_status',
        'notes',
        'return_refund_status',
        'gcash_transaction_id',
        'shipping_address'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    protected static function booted()
    {
        static::created(function ($order) {
            try {
                // Load all necessary relationships
                $order->load(['items.product', 'customer']);

                // Generate PDF with debug info
                Log::info('Generating invoice for order: ' . $order->reference_number);
                Log::info('Items count: ' . $order->items->count());

                // Generate PDF
                $pdf = PDF::loadView('invoices.template', [
                    'order' => $order,
                    'items' => $order->items->map(function($item) {
                        return [
                            'name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'unit_amount' => $item->unit_amount,
                            'total' => $item->quantity * $item->unit_amount
                        ];
                    })
                ]);

                // Generate filename
                $filename = 'invoice-' . $order->reference_number . '.pdf';

                // Store the PDF
                Storage::disk('public')->put('invoices/' . $filename, $pdf->output());

                // Update order with invoice filename
                $order->e_invoice = $filename;
                $order->save();

                Log::info('Invoice generated successfully: ' . $filename);
            } catch (\Exception $e) {
                Log::error('Invoice generation failed: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        });
    }
}
