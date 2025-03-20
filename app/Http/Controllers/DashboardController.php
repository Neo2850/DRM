<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Orders;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $currentWeek = Carbon::now()->startOfWeek();

        // Get monthly revenue data for chart
        $monthlyRevenue = Orders::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(total_amount) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $metrics = [
            'total_revenue' => Orders::sum('total_amount'),
            'yearly_revenue' => Orders::whereYear('created_at', date('Y'))->sum('total_amount'),
            'daily_revenue' => Orders::whereDate('created_at', Carbon::today())->sum('total_amount'),
            'total_customers' => Customer::count(),
        ];

        // Get most sold items
        $mostSoldItems = DB::table('order_items')
            ->select('products.name', DB::raw('COUNT(*) as total_sold'))
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(8)
            ->get()
            ->map(function ($item){
                $maxSold = DB::table('order_items')->select(DB::raw('COUNT(*) as count'))
                    ->groupBy('product_id')->orderByDesc('count')->first()->count;
                return [
                    'name' => $item->name,
                    'percentage' => round(($item->total_sold / $maxSold) * 100)
                ];
            });

        // Get latest orders
        $latestOrders = Orders::with(['customer', 'items.product'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'product' => $order->items->first()->product->name,
                    'date' => $order->created_at->format('M d,Y'),
                    'customer' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'status' => $order->order_status,
                    'amount' => number_format($order->total_amount, 2)
                ];
            });

        return Inertia::render("AdminSide/Dashboard", [
            'metrics' => $metrics,
            'mostSoldItems' => $mostSoldItems,
            'latestOrders' => $latestOrders,
            'monthlyRevenue' => $monthlyRevenue
        ]);
    }

    private function calculateTrend($type, $currentPeriod, $lastPeriod)
    {
        if ($type === 'amount') {
            $current = Orders::where('created_at', '>=', $currentPeriod)
                ->sum('total_amount');

            $last = Orders::where('created_at', '>=', $lastPeriod)
                ->where('created_at', '<', $currentPeriod)
                ->sum('total_amount');
        } else {
            $current = Orders::where('created_at', '>=', $currentPeriod)->count();
            $last = Orders::where('created_at', '>=', $lastPeriod)
                ->where('created_at', '<', $currentPeriod)
                ->count();
        }

        if ($last === 0) return 0;
        return round((($current - $last) / $last) * 100);
    }
}
