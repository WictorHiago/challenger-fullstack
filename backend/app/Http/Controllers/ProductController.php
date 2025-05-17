<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products with pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search by name or description
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get paginated results
        $products = $query->paginate(10);
        
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:200',
            'price' => 'required|numeric|min:0',
            'validity_date' => 'required|date|after_or_equal:today',
            'image_url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'validity_date' => $request->validity_date,
            'image' => basename($request->image_url), // Apenas o nome do arquivo extraído da URL
            'image_url' => $request->image_url,
            'category_id' => $request->category_id,
        ]);

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:50',
            'description' => 'sometimes|string|max:200',
            'price' => 'sometimes|numeric|min:0',
            'validity_date' => 'sometimes|date|after_or_equal:today',
            'image_url' => 'sometimes|url',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        // Update image_url if provided
        if ($request->has('image_url')) {
            $product->image = basename($request->image_url); // Apenas o nome do arquivo extraído da URL
            $product->image_url = $request->image_url;
        }

        // Update other fields if provided
        if ($request->has('name')) {
            $product->name = $request->name;
        }
        
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        
        if ($request->has('price')) {
            $product->price = $request->price;
        }
        
        if ($request->has('validity_date')) {
            $product->validity_date = $request->validity_date;
        }
        
        if ($request->has('category_id')) {
            $product->category_id = $request->category_id;
        }

        $product->save();

        return response()->json($product);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Search products by name or description.
     *
     * @param  string  $term
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($term)
    {
        $products = Product::with('category')
            ->where('name', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->paginate(10);

        return response()->json($products);
    }
}
