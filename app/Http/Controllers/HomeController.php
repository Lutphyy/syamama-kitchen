<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Only get active products that are marked as showcase
        $products = Product::where('is_active', true)
            ->where('is_showcase', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('products', 'categories'));
    }
}
