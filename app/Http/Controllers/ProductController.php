<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // مهم للتعامل مع الملفات

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // جلب منتج واحد فقط بناءً على الـ ID
    public function show($id)
    {
        $product = Product::find($id);
        
        if ($product) {
            return response()->json($product);
        }
        return response()->json(['message' => 'Product not found!'], 404);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // إذا الطلب يحتوي على ملف صورة، احفظها
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = url('storage/' . $path); // نحفظ الرابط كامل
        }

        $product = Product::create($data);
        
        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        
        if ($product) {
            $data = $request->all();

            // إذا رفع صورة جديدة، نحفظها (ونقدر نحذف القديمة لاحقاً)
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = url('storage/' . $path);
            }

            $product->update($data);
            return response()->json(['message' => 'Product updated!'], 200);
        }
        
        return response()->json(['message' => 'Not found!'], 404);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        
        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted!'], 200);
        }
        return response()->json(['message' => 'Not found!'], 404);
    }
}