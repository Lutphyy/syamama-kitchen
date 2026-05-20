<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();

        // Sales trend data (last 30 days)
        $salesData = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentOrders = Order::with('items')->latest()->take(10)->get();

        // Monthly revenue for chart
        $monthlyRevenue = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'todayRevenue',
            'pendingOrders', 'salesData', 'recentOrders', 'monthlyRevenue'
        ));
    }
}
