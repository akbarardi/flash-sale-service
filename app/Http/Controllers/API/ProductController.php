<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('orderItems')->get();
        
        if (!empty($products)) {
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => "Product tidak ditemukan",
        ], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);
        
        try {
            $product = Product::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => "Product berhasil dibuat",
                'data' => $product
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }
}
