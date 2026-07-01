<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->latest()
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('user.cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'selected_size' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->status !== 'active' || $product->stock <= 0) {
            return back()->with('error', 'Produk tidak tersedia saat ini.');
        }

        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Validate size if product has sizes
        if ($product->sizes && count($product->sizes) > 0) {
            if (!$request->selected_size || !in_array($request->selected_size, $product->sizes)) {
                return back()->with('error', 'Silakan pilih ukuran yang valid.');
            }
        }

        // Check if same product+size already in cart
        $existingItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->where('selected_size', $request->selected_size)
            ->first();

        if ($existingItem) {
            $newQty = $existingItem->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Total quantity melebihi stok. Sudah ada ' . $existingItem->quantity . ' di keranjang.');
            }
            $existingItem->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'selected_size' => $request->selected_size,
            ]);
        }

        return redirect()->route('user.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->quantity > $cart->product->stock) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $cart->product->stock);
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
