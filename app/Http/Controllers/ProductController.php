<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Suppliers;
use App\Traits\LoadsBrandData;
use App\Traits\LoadsProductData;
use App\Traits\LoadsCategoryData;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    use LoadsBrandData;
    use LoadsProductData;
    use LoadsCategoryData;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    public function create()
    {
        $suppliers = Suppliers::orderBy('supplier_name')->get();
        return view('PRODUCT.add_product', array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('suppliers')
        ));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        Product::create($data);
        return redirect()->route('product.add')->with('success', 'Product created successfully.');
    }

    /**
     * Apply reusable filters to product query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyProductFilters($query, Request $request)
    {
        // Category filter
        if ($request->filled('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Brand filter
        if ($request->filled('brand') && $request->brand !== '') {
            $query->where('brand_id', $request->brand);
        }

        return $query;
    }

    /**
     * Apply search functionality across multiple product fields.
     *
     * @param  \Illuminate\Support\Collection  $products
     * @param  string  $search
     * @return \Illuminate\Support\Collection
     */
    protected function applyProductSearch($products, $search)
    {
        if (empty($search)) {
            return $products;
        }

        $search = strtolower($search);

        return $products->filter(function ($product) use ($search) {
            // Search in product name
            if (stripos($product->product_name ?? '', $search) !== false) {
                return true;
            }

            // Search in serial number
            if (stripos($product->serial_number ?? '', $search) !== false) {
                return true;
            }

            // Search in warranty period
            if (stripos($product->warranty_period ?? '', $search) !== false) {
                return true;
            }

            // Search in brand name
            if ($product->brand && stripos($product->brand->brand_name ?? '', $search) !== false) {
                return true;
            }

            // Search in category name
            if ($product->category && stripos($product->category->category_name ?? '', $search) !== false) {
                return true;
            }

            // Search in date added (formatted)
            if ($product->created_at) {
                $formattedDate = \Carbon\Carbon::parse($product->created_at)->format('M d, Y');
                if (stripos($formattedDate, $search) !== false) {
                    return true;
                }
                // Also search in raw date format
                if (stripos($product->created_at->format('Y-m-d'), $search) !== false) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(Request $request): View
    {
        $query = Product::with('brand', 'category', 'supplier');

        // Apply filters
        $query = $this->applyProductFilters($query, $request);

        // Get products
        $products = $query->get();

        // Apply search
        if ($request->filled('search')) {
            $products = $this->applyProductSearch($products, $request->search);
        }

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('products')
        );

        // If HTMX request, return only the table partial
        if ($request->header('HX-Request')) {
            return view('partials.productTable_Inventory', $data);
        }

        // Otherwise return full view
        return view('DASHBOARD.inventory', $data);
    }

    /**
     * Display inventory list with search and filtering functionality.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    // FOR INVENTORY LIST FOR QUANTITY SUMMING AT invetory_list.blade.php
    public function inventoryList(Request $request)
    {
        $query = Product::with('brand', 'category', 'supplier');

        // Apply reusable filters
        $query = $this->applyProductFilters($query, $request);

        // Get all products for grouping
        $allProducts = $query->get();

        // Group by product_name and calculate total quantity
        $grouped = $allProducts->groupBy('product_name')->map(function ($group) {
            $first = $group->first();
            $quantity = $group->count();

            return (object) [
                'id' => $first->id,
                'product_name' => $first->product_name,
                'brand' => $first->brand,
                'category' => $first->category,
                'supplier' => $first->supplier,
                'quantity' => $quantity,
                'price' => $first->price,
                'serial_number' => $first->serial_number,
                'warranty_period' => $first->warranty_period,
            ];
        })->values();

        // Search functionality - search across all table columns (except actions)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $grouped = $grouped->filter(function ($item) use ($search) {
                // Search in product name
                if (stripos($item->product_name, $search) !== false) {
                    return true;
                }

                // Search in brand name
                if ($item->brand && stripos($item->brand->brand_name, $search) !== false) {
                    return true;
                }

                // Search in category name
                if ($item->category && stripos($item->category->category_name, $search) !== false) {
                    return true;
                }

                // Search in quantity (as string)
                if (stripos((string)$item->quantity, $search) !== false) {
                    return true;
                }

                // Search in price (as formatted string)
                $formattedPrice = number_format($item->price ?? 0, 2);
                if (stripos($formattedPrice, $search) !== false) {
                    return true;
                }

                // Search in raw price value
                if (stripos((string)($item->price ?? 0), $search) !== false) {
                    return true;
                }

                return false;
            });
        }

        $products = $grouped->values();

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('products')
        );

        // If HTMX request, return only the table partial
        if ($request->header('HX-Request')) {
            return view('partials.productTable_InventList', $data);
        }

        // Otherwise return full view
        return view('DASHBOARD.inventory_list', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
