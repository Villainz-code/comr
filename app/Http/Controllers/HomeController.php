<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('status', 'active')
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
