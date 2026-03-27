<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = $request->user()->id;

        $productQuery = Product::query()->where('user_id', $userId);
        $listingQuery = Listing::query()->where('user_id', $userId);
        $orderQuery = Order::query()->where('user_id', $userId);

        return Inertia::render('Dashboard', [
            'stats' => [
                'products' => $productQuery->count(),
                'active_listings' => (clone $listingQuery)->whereIn('status', ['active', 'listed'])->count(),
                'pending_orders' => (clone $orderQuery)->where('fulfillment_status', 'pending')->count(),
                'low_stock' => (clone $productQuery)->where('stock_quantity', '<=', 5)->count(),
                'potential_profit' => (clone $productQuery)->get()->sum(fn (Product $product) => $product->potential_profit),
            ],
            'recent_products' => (clone $productQuery)
                ->latest()
                ->limit(5)
                ->get(),
            'recent_listings' => (clone $listingQuery)
                ->with('product')
                ->latest()
                ->limit(5)
                ->get(),
            'recent_orders' => (clone $orderQuery)
                ->with(['product', 'listing'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
