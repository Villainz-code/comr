<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')->with('category');

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(9);
        $categories = Category::withCount(['products' => fn($q) => $q->where('status', 'active')])->get();

        return view('user.shop.index', compact('products', 'categories'));
    }
}
