<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Get all products
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Get a single product by ID
    public function show($id)
    {
        $product = Product::find($id);

        if ($product) {
            return response()->json($product);
        }
        return response()->json(['message' => 'Product not found!'], 404);
    }

    // Create a new product
    public function store(Request $request)
    {
        $data = $request->all();

        // If the request contains an image file, save it
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path; // Save only the relative path
        }

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product added successfully!',
            'product' => $product
        ], 201);
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        
        if ($product) {
            $data = $request->all();

            // If a new image is uploaded, save it
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path; // Save only the relative path
            }

            $product->update($data);
            return response()->json(['message' => 'Product updated!'], 200);
        }
        
        return response()->json(['message' => 'Not found!'], 404);
    }

    // Delete a product
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