<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        // Jika dari cancelled ke status lain, kurangi stok kembali
        if ($oldStatus === 'cancelled' && $request->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }
        }

        // Jika di-cancel, kembalikan stok
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status order berhasil diperbarui! ✨');
    }

    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return response()->json($order);
    }
}
