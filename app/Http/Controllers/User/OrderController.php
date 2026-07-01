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
            'shipping_address' => ['required', 'string', 'max:250'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string'],
            'shipping_method' => ['required', 'in:regular,express'],
            'payment_method' => ['required', 'in:transfer,ewallet,cod'],
            'payment_channel' => ['nullable', 'string'],
        ], [
            'product_id.required' => 'Produk tidak valid.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Validate size if product has sizes
        if ($product->sizes && count($product->sizes) > 0) {
            if (!$request->selected_size || !in_array($request->selected_size, $product->sizes)) {
                return back()->with('error', 'Silakan pilih ukuran yang valid.')->withInput();
            }
        }

        $shipping_cost = $request->shipping_method === 'express' ? 25000 : 15000;
        $total_price = ($product->price * $request->quantity) + $shipping_cost;

        Order::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'selected_size' => $request->selected_size,
            'total_price' => $total_price,
            'shipping_address' => $request->shipping_address,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'country' => $request->country,
            'shipping_method' => $request->shipping_method,
            'shipping_cost' => $shipping_cost,
            'payment_method' => $request->payment_method,
            'payment_channel' => $request->payment_channel,
            'shipping_note' => $request->shipping_note,
            'status' => 'pending',
        ]);

        // Kurangi stok
        $product->decrement('stock', $request->quantity);

        return redirect()->route('user.orders')
            ->with('success', 'Pesanan berhasil dibuat! Pesanan Anda sedang menunggu konfirmasi.');
    }

    public function edit(Order $order)
    {
        // Ensure user owns the order and status is pending
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            return redirect()->route('user.orders')->with('error', 'Pesanan tidak dapat diedit.');
        }

        $product = $order->product;
        return view('user.orders.edit', compact('order', 'product'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            return redirect()->route('user.orders')->with('error', 'Pesanan tidak dapat diedit.');
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'shipping_address' => ['required', 'string', 'max:250'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string'],
            'shipping_method' => ['required', 'in:regular,express'],
            'payment_method' => ['required', 'in:transfer,ewallet,cod'],
            'payment_channel' => ['nullable', 'string'],
        ], [
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.min' => 'Jumlah minimal 1.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        $product = $order->product;

        // Check stock if quantity changed
        $qtyDiff = $request->quantity - $order->quantity;
        if ($qtyDiff > 0 && $product->stock < $qtyDiff) {
            return back()->with('error', 'Stok tidak mencukupi untuk tambahan pesanan.')->withInput();
        }

        // Validate size if product has sizes
        if ($product->sizes && count($product->sizes) > 0) {
            if (!$request->selected_size || !in_array($request->selected_size, $product->sizes)) {
                return back()->with('error', 'Silakan pilih ukuran yang valid.')->withInput();
            }
        }

        $shipping_cost = $request->shipping_method === 'express' ? 25000 : 15000;
        $total_price = ($product->price * $request->quantity) + $shipping_cost;

        $order->update([
            'quantity' => $request->quantity,
            'selected_size' => $request->selected_size,
            'total_price' => $total_price,
            'shipping_address' => $request->shipping_address,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'city' => $request->city,
            'country' => $request->country,
            'shipping_method' => $request->shipping_method,
            'shipping_cost' => $shipping_cost,
            'payment_method' => $request->payment_method,
            'payment_channel' => $request->payment_channel,
            'shipping_note' => $request->shipping_note,
        ]);

        // Adjust stock if quantity changed
        if ($qtyDiff !== 0) {
            $product->decrement('stock', $qtyDiff);
        }

        return redirect()->route('user.orders')
            ->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->status !== 'pending') {
            return redirect()->route('user.orders')->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);
        
        // Return stock
        $order->product->increment('stock', $order->quantity);

        return redirect()->route('user.orders')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
