<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function create(Product $product)
    {
        if ($product->status !== 'active' || $product->stock <= 0) {
            return redirect()->route('user.shop')
                ->with('error', 'Produk tidak tersedia saat ini.');
        }

        return view('user.orders.create', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'string', 'max:500'],
        ], [
            'product_id.required' => 'Produk tidak valid.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $product->price * $request->quantity,
            'shipping_address' => $request->shipping_address,
            'status' => 'pending',
        ]);

        // Kurangi stok
        $product->decrement('stock', $request->quantity);

        return redirect()->route('user.orders')
            ->with('success', 'Pesanan berhasil dibuat! Pesanan Anda sedang menunggu konfirmasi.');
    }
}
