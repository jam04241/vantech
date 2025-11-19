<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Suppliers;
use App\Models\Product_Stocks;
use App\Traits\LoadsBrandData;
use App\Traits\LoadsProductData;
use App\Traits\LoadsCategoryData;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($data) {
            $productData = collect($data)->except(['price'])->toArray();
            $product = Product::create($productData);

            Product_Stocks::create([
                'product_id' => $product->id,
                'price' => $data['price'],
                'stock_quantity' => 1,
            ]);
        });

        return redirect()->route('product.add')->with('success', 'Product saved and stock updated successfully.');
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyProductSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        $search = strtolower($search);
        $like = '%' . $search . '%';

        return $query->where(function ($q) use ($search, $like) {
            $q->whereRaw('LOWER(products.product_name) LIKE ?', [$like])
                ->orWhereRaw('LOWER(products.serial_number) LIKE ?', [$like])
                ->orWhereRaw('LOWER(products.warranty_period) LIKE ?', [$like])
                ->orWhereHas('brand', function ($brand) use ($like) {
                    $brand->whereRaw('LOWER(brand_name) LIKE ?', [$like]);
                })
                ->orWhereHas('category', function ($category) use ($like) {
                    $category->whereRaw('LOWER(category_name) LIKE ?', [$like]);
                })
                ->orWhereHas('stock', function ($stock) use ($like) {
                    $stock->whereRaw('LOWER(CAST(stock_quantity AS VARCHAR(50))) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(CAST(price AS VARCHAR(50))) LIKE ?', [$like]);
                });
        });
    }

    /**
     * Apply ordering logic for common sort options.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applySorting($query, Request $request)
    {
        $sort = $request->get('sort', 'name_asc');

        switch ($sort) {
            case 'name_desc':
                $query->orderBy('products.product_name', 'desc');
                break;
            case 'qty_desc':
                $query->orderBy(
                    Product_Stocks::select('stock_quantity')
                        ->whereColumn('product_id', 'products.id'),
                    'desc'
                );
                break;
            case 'qty_asc':
                $query->orderBy(
                    Product_Stocks::select('stock_quantity')
                        ->whereColumn('product_id', 'products.id'),
                    'asc'
                );
                break;
            case 'price_desc':
                $query->orderBy(
                    Product_Stocks::select('price')
                        ->whereColumn('product_id', 'products.id'),
                    'desc'
                );
                break;
            case 'price_asc':
                $query->orderBy(
                    Product_Stocks::select('price')
                        ->whereColumn('product_id', 'products.id'),
                    'asc'
                );
                break;
            case 'name_asc':
            default:
                $query->orderBy('products.product_name', 'asc');
                break;
        }

        return $query;
    }


    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(Request $request): View
    {
        $query = Product::with('brand', 'category', 'supplier', 'stock');

        // Apply filters
        $query = $this->applyProductFilters($query, $request);

        // Apply search
        if ($request->filled('search')) {
            $query = $this->applyProductSearch($query, $request->search);
        }

        $query = $this->applySorting($query, $request);

        // Get products
        $products = $query->get();

        $suppliers = Suppliers::orderBy('supplier_name')->get();

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('products', 'suppliers'),
            ['currentSort' => $request->get('sort', 'name_asc')]
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
        $query = Product::with('brand', 'category', 'supplier', 'stock');

        // Apply reusable filters
        $query = $this->applyProductFilters($query, $request);

        // Apply search
        if ($request->filled('search')) {
            $query = $this->applyProductSearch($query, $request->search);
        }

        $productsCollection = $query->get();

        $grouped = $productsCollection->groupBy(function ($product) {
            return implode('|', [
                $product->product_name,
                $product->brand_id ?? 'null',
                $product->category_id ?? 'null',
            ]);
        })->map(function ($group) {
            $first = $group->first();
            return (object) [
                'id' => $first->id,
                'product_name' => $first->product_name,
                'brand' => $first->brand,
                'category' => $first->category,
                'brand_id' => $first->brand_id,
                'category_id' => $first->category_id,
                'quantity' => $group->count(),
                'price' => $first->stock?->price ?? 0,
            ];
        })->values();

        $sort = $request->get('sort', 'name_asc');
        $products = match ($sort) {
            'name_desc' => $grouped->sortByDesc('product_name')->values(),
            'qty_desc' => $grouped->sortByDesc('quantity')->values(),
            'qty_asc' => $grouped->sortBy('quantity')->values(),
            'price_desc' => $grouped->sortByDesc('price')->values(),
            'price_asc' => $grouped->sortBy('price')->values(),
            default => $grouped->sortBy('product_name')->values(),
        };

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('products'),
            ['currentSort' => $sort]
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
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        DB::transaction(function () use ($product, $data) {
        $productData = collect($data)->except(['price'])->toArray();
        $productData['serial_number'] = $productData['serial_number'] ?? ($product->serial_number ?? 'N/A');

            $product->update($productData);

            $stock = Product_Stocks::firstOrNew(['product_id' => $product->id]);
            $stock->price = $data['price'];
        $stock->stock_quantity = $stock->stock_quantity ?? 1;
            $stock->save();
        });

        return redirect()->route('inventory')->with('success', 'Product updated successfully.');
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
