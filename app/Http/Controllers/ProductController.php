<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'target_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'listing_status' => ['required', 'in:draft,ready,active,paused,sold,archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $request->user()->products()->create($validated);

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product, Request $request)
    {
        $this->authorizeProduct($product, $request);

        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product, $request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'target_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'listing_status' => ['required', 'in:draft,ready,active,paused,sold,archived'],
            'notes' => ['nullable', 'string'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product, Request $request)
    {
        $this->authorizeProduct($product, $request);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product removed.');
    }

    private function authorizeProduct(Product $product, Request $request): void
    {
        abort_unless($product->user_id === $request->user()->id, 403);
    }
}
