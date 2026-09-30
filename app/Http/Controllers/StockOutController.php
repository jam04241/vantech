<?php

namespace App\Http\Controllers;

use App\Models\CustomerPurchaseOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    /**
     * Display stock out records (products with stock_quantity = 0)
     */
    public function index(Request $request)
    {
        // Get all products where stock_quantity = 0 (sold out)
        $query = Product::with([
            'brand',
            'category',
            'stock',
            'customerPurchaseOrders.drTransaction',
            'customerPurchaseOrders.customer',
            'customerPurchaseOrders.warrantyClaim.recordedBy',
        ])
            ->whereHas('stock', function ($q) {
                $q->where('stock_quantity', 0);
            });

        // Sold items vs items returned broken under warranty
        $status = $request->get('status', '');
        $claimed = fn ($lines) => $lines->where('status', CustomerPurchaseOrder::STATUS_WARRANTY_CLAIM);
        if ($status === 'claimed') {
            $query->whereHas('customerPurchaseOrders', $claimed);
        } elseif ($status === 'sold') {
            $query->whereDoesntHave('customerPurchaseOrders', $claimed);
        }

        // Apply search across multiple columns
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('warranty_period', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($b) use ($search) {
                        $b->where('brand_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($c) use ($search) {
                        $c->where('category_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customerPurchaseOrders.drTransaction', function ($dr) use ($search) {
                        $dr->where('receipt_no', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date filtering
        if ($request->filled('date')) {
            $date = $request->get('date');
            $query->whereDate('products.created_at', $date);
        }

        // Apply sorting
        $sort = $request->get('sort', 'date_added_desc');
        $query = $this->applySorting($query, $sort);

        // Get pagination (7 items per page)
        $products = $query->paginate(7)->withQueryString();

        $data = array_merge(WarrantyClaimController::stockOutStats(), [
            'products' => $products,
            'currentSort' => $sort,
            'currentStatus' => $status,
            'searchQuery' => $request->get('search', ''),
            'selectedDate' => $request->get('date', ''),
        ]);

        // If HTMX request, return only the table partial
        if ($request->header('HX-Request')) {
            return view('INVENTORY.partials.stock_out_table', $data);
        }

        // Otherwise return full view with all data
        return view('INVENTORY.stock_out', $data);
    }

    /**
     * Apply sorting to the query
     */
    private function applySorting($query, $sort)
    {
        return match ($sort) {
            'brand_asc' => $query->join('brands', 'products.brand_id', '=', 'brands.id')
                ->select('products.*')
                ->orderBy('brands.brand_name', 'asc'),
            'brand_desc' => $query->join('brands', 'products.brand_id', '=', 'brands.id')
                ->select('products.*')
                ->orderBy('brands.brand_name', 'desc'),
            'category_asc' => $query->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*')
                ->orderBy('categories.category_name', 'asc'),
            'category_desc' => $query->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*')
                ->orderBy('categories.category_name', 'desc'),
            'date_added_asc' => $query->orderBy('products.created_at', 'asc'),
            'date_added_desc' => $query->orderBy('products.created_at', 'desc'),
            default => $query->orderBy('products.created_at', 'desc'),
        };
    }
}
