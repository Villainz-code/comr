<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product'])->latest();

        if ($request->has('status') && in_array($request->status, ['pending', 'processed', 'shipped', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,shipped,completed,cancelled',
            'estimated_arrival' => 'nullable|string|max:255'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'estimated_arrival' => $request->estimated_arrival
        ]);

        // If cancelled by admin, and it wasn't cancelled before, restore stock
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $order->product->increment('stock', $order->quantity);
        }

        // If un-cancelled by admin, deduct stock
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            $order->product->decrement('stock', $order->quantity);
        }

        return redirect()->route('admin.orders')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
