<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong! 🛒');
        }

        $total = 0;
        $cartItems = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
                $total += $product->price * $item['quantity'];
            }
        }

        return view('checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_note' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong!');
        }

        $total = 0;
        $orderItems = [];
        $waItems = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];

                $waItems[] = "🍰 {$product->name} x{$item['quantity']} — Rp " . number_format($subtotal, 0, ',', '.');

                // Kurangi stok
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Buat order
        $order = Order::create([
            'order_code' => Order::generateOrderCode(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'customer_note' => $request->customer_note,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        // Buat order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        // Hapus cart
        session()->forget('cart');

        // Generate WhatsApp message
        $waNumber = env('WHATSAPP_NUMBER', '6281234567890');
        $message = "🛒 *Order Baru - Syamama Kitchen*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "📋 Kode: {$order->order_code}\n";
        $message .= "👤 Nama: {$request->customer_name}\n";
        $message .= "📱 Telp: {$request->customer_phone}\n";
        $message .= "📍 Alamat: {$request->customer_address}\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= implode("\n", $waItems) . "\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💰 *Total: Rp " . number_format($total, 0, ',', '.') . "*\n";

        if ($request->customer_note) {
            $message .= "📝 Catatan: {$request->customer_note}\n";
        }

        $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($message);

        return redirect()->away($waUrl);
    }
}
