<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->get();
        $suppliers = Supplier::all();

        return view('admin.index', compact(
            'categories',
            'products',
            'suppliers'
        ));
    }
}
