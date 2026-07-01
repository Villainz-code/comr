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

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            return redirect()->route('user.shop')
                ->with('error', 'Produk tidak tersedia.');
        }

        $relatedProducts = Product::where('status', 'active')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('user.shop.show', compact('product', 'relatedProducts'));
    }
}
