<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->reference_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 30px;
            text-align: center;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 30px;
            text-align: right;
        }
        .total-section p {
            margin: 5px 0;
        }
        .total-amount {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ArtzworkPC</h1>
        <p>Phase 4 Package 4 Bagong Silang Caloocan City</p>
        <p>Phone: 09175507848 / 09975937774</p>
        <p>Email: artwork00@gmail.com</p>
    </div>

    <div class="invoice-info">
        <h2 style="text-align: center; margin-bottom: 20px;">INVOICE</h2>
        <p><strong>Invoice #:</strong> {{ $order->reference_number }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
        <p><strong>Customer:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
        <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th style="width: 100px;">Quantity</th>
                <th style="width: 150px;">Unit Price</th>
                <th style="width: 150px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp

            @foreach($order->items as $item)
                @php
                    $itemTotal = $item->quantity * $item->unit_amount;
                    $subtotal += $itemTotal;
                @endphp
                <tr>
                    <td>{{ $item->product->name }}<br><small>SKU: {{ $item->product->sku }}</small></td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">₱{{ number_format($item->unit_amount, 2) }}</td>
                    <td class="text-right">₱{{ number_format($itemTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p>
            <strong>Subtotal:</strong>
            <span style="margin-left: 20px;">₱{{ number_format($subtotal, 2) }}</span>
        </p>
        <p>
            <strong>Shipping Fee:</strong>
            <span style="margin-left: 20px;">₱{{ number_format($order->shipping_fee ?? 0, 2) }}</span>
        </p>
        <p class="total-amount">
            <strong>Total Amount:</strong>
            <span style="margin-left: 20px;">₱{{ number_format($order->total_amount, 2) }}</span>
        </p>
    </div>
</body>
</html>
