<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();

        // Period filter
        $period = $request->get('period', '30');
        $days = match($period) {
            '7' => 7,
            '30' => 30,
            '90' => 90,
            '365' => 365,
            default => 30,
        };

        // Sales trend data
        $salesData = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue data grouped appropriately
        if ($days <= 30) {
            $revenueData = $salesData; // daily
            $revenueLabels = $salesData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
            $revenueTotals = $salesData->pluck('total');
        } else {
            // Group by month for longer periods (MySQL compatible)
            $revenueData = Order::where('status', '!=', 'cancelled')
                ->where('created_at', '>=', now()->subDays($days))
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('SUM(total_amount) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            $revenueLabels = $revenueData->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
            $revenueTotals = $revenueData->pluck('total');
        }

        $recentOrders = Order::with('items')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'todayRevenue',
            'pendingOrders', 'salesData', 'recentOrders',
            'revenueLabels', 'revenueTotals', 'period'
        ));
    }

    public function admins()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return back()->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function destroyAdmin($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        
        // Prevent deleting yourself
        if ($admin->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        // Prevent deleting if only 1 admin left
        if (User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir!');
        }

        $admin->delete();
        return back()->with('success', 'Admin berhasil dihapus!');
    }
}
