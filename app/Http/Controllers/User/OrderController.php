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

        $shippingMethod = $request->input('shipping_method', 'regular');
        $shippingCost = ($shippingMethod === 'express') ? 25000 : 15000;
        $totalPrice = ($product->price * $request->quantity) + $shippingCost;

        $user = auth()->user();
        $cityName = '';
        if ($user->regency && $user->district) {
            $cityName = $user->regency->name . ', ' . $user->district->name;
        } elseif ($user->regency) {
            $cityName = $user->regency->name;
        }

        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'selected_size' => $request->input('selected_size'),
            'total_price' => $totalPrice,
            'shipping_address' => $request->shipping_address,
            'recipient_name' => $request->input('recipient_name', $user->name),
            'phone' => $request->input('phone', $user->phone),
            'email' => $request->input('email', $user->email),
            'city' => $cityName,
            'country' => $request->input('country', 'Indonesia'),
            'shipping_method' => $shippingMethod,
            'shipping_cost' => $shippingCost,
            'payment_method' => $request->input('payment_method'),
            'payment_channel' => $request->input('payment_channel'),
            'shipping_note' => $request->input('shipping_note'),
            'status' => 'pending',
        ]);

        // Kurangi stok
        $product->decrement('stock', $request->quantity);

        return redirect()->route('user.orders')
            ->with('success', 'Pesanan berhasil dibuat! Pesanan Anda sedang menunggu konfirmasi.');
    }
}
