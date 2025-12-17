<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Models\CustomerPurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Main query from products table
        $query = Product::with(['category', 'brand']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('category_name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('brand', function ($brandQuery) use ($search) {
                        $brandQuery->where('brand_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Get all products
        $allProducts = $query->get();

        // Group by product_name and product_condition to avoid duplicates
        $groupedProducts = $allProducts->groupBy(function ($item) {
            return $item->product_name . '|' . $item->product_condition;
        });

        $productsCollection = $groupedProducts->map(function ($group) use ($startDate, $endDate) {
            $firstItem = $group->first();

            // Get all product IDs for this product name and condition
            $productIds = $group->pluck('id');

            // Count sold from customer_purchase_orders grouped by product_name
            $soldQuery = CustomerPurchaseOrder::whereIn('product_id', $productIds);

            if ($startDate && $endDate) {
                $soldQuery->whereBetween('order_date', [$startDate, $endDate]);
            }

            $totalSold = $soldQuery->count();

            // Count availability: number of stock records with stock_quantity = 1
            $availableStock = Product_Stocks::whereIn('product_id', $productIds)
                ->where('stock_quantity', 1)
                ->count();

            // Get price from first product's stock
            $price = Product_Stocks::where('product_id', $firstItem->id)->value('price') ?? 0;

            return [
                'product_name' => $firstItem->product_name,
                'product_condition' => $firstItem->product_condition,
                'category' => $firstItem->category ? $firstItem->category->category_name : 'N/A',
                'brand' => $firstItem->brand ? $firstItem->brand->brand_name : 'N/A',
                'price' => $price,
                'sold' => $totalSold,
                'availability' => $availableStock,
                'status' => $availableStock > 0 ? 'In Stock' : 'Out of Stock',
            ];
        })->values();

        // Calculate totals before pagination
        $totalProducts = $productsCollection->count();
        $totalSold = $productsCollection->sum('sold');
        $totalAvailableStock = $productsCollection->sum('availability');
        $totalPrice = $productsCollection->sum('price');
        $totalRevenue = $productsCollection->sum(function ($product) {
            return $product['price'] * $product['sold'];
        });

        // Paginate with 50 items per page
        $currentPage = $request->input('page', 1);
        $perPage = 50;
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $productsCollection->forPage($currentPage, $perPage),
            $productsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('DASHBOARD.inventoryReports', compact(
            'products',
            'totalProducts',
            'totalSold',
            'totalAvailableStock',
            'totalPrice',
            'totalRevenue',
            'search',
            'startDate',
            'endDate'
        ));
    }
}
