<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'processed_orders' => Order::where('user_id', $user->id)->where('status', 'processed')->count(),
            'completed_orders' => Order::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $recentOrders = Order::where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentOrders'));
    }
}
