<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return Inertia::render('Products', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        if (Product::count() >= 4) {
            return redirect()->back()->withErrors(['message' => 'Maksimal 4 produk yang diperbolehkan.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|string', // We will accept string from textarea and split by newline
            'is_recommended' => 'boolean'
        ]);

        if (!empty($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        } else {
            $validated['features'] = [];
        }

        if (!isset($validated['is_recommended'])) {
            $validated['is_recommended'] = false;
        }

        Product::create($validated);

        return redirect()->back()->with('message', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'price' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'is_recommended' => 'boolean'
        ]);

        if (!empty($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        } else {
            $validated['features'] = [];
        }

        if (!isset($validated['is_recommended'])) {
            $validated['is_recommended'] = false;
        }

        $product->update($validated);

        return redirect()->back()->with('message', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('message', 'Product deleted successfully.');
    }
}
