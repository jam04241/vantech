<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Suppliers;
use App\Models\Product_Stocks;
use App\Traits\LoadsBrandData;
use App\Traits\LoadsProductData;
use App\Traits\LoadsCategoryData;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use LoadsBrandData;
    use LoadsProductData;
    use LoadsCategoryData;
    use LogsAuditTrail;

    public function create()
    {
        $suppliers = Suppliers::where('status', 'active')->orderBy('supplier_name')->get();
        return view('PRODUCT.add_product', array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('suppliers')
        ));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        // Determine product condition based on checkbox
        $productCondition = $request->has('is_used') ? 'Second Hand' : 'Brand New';

        // If product is Second Hand, set supplier_id to null
        if ($request->has('is_used')) {
            $data['supplier_id'] = null;
        }

        // Add product condition to data
        $data['product_condition'] = $productCondition;

        // Get price before creating product
        $price = $data['price'] ?? 0;
        unset($data['price']);

        DB::beginTransaction();

        try {
            // Always create new product (no archive checking)
            $product = Product::create($data);

            if ($product) {
                Product_Stocks::create([
                    'product_id' => $product->id,
                    'stock_quantity' => 1,
                    'price' => $price,
                ]);
            }

            // Get brand name for description
            $brand = $product->brand;
            $brandName = $brand ? $brand->brand_name : 'Unknown';

            // Log the product creation
            $description = "Added new product: {$brandName} {$data['product_name']} (SKU: {$data['serial_number']})";
            $logData = array_merge($data, ['price' => $price]);
            $this->logCreateAudit('CREATE', 'Inventory', $description, $logData, $request);

            DB::commit();

            return redirect()->route('product.add')
                ->with('success', 'Product "' . $data['product_name'] . '" has been successfully registered!')
                ->with('clear_form', true);
        } catch (\Exception $e) {
            DB::rollBack();

            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'serial_number')) {
                return back()->with('error', 'Serial number "' . $data['serial_number'] . '" already exists. Please use a different serial number.')->withInput();
            }

            return back()->with('error', 'Failed to create product: ' . $e->getMessage())->withInput();
        }
    }

    protected function applyProductFilters($query, Request $request)
    {
        // Category filter - handle multiple categories
        if ($request->filled('categories')) {
            $categories = $request->input('categories', []);
            if (!empty($categories) && is_array($categories)) {
                $query->whereIn('category_id', $categories);
            }
        } elseif ($request->filled('category') && $request->category !== '') {
            // Fallback for single category (backward compatibility)
            $query->where('category_id', $request->category);
        }

        // Brand filter
        if ($request->filled('brand') && $request->brand !== '') {
            $query->where('brand_id', $request->brand);
        }

        // Condition filter
        if ($request->filled('condition') && $request->condition !== '') {
            $query->where('product_condition', $request->condition);
        }

        // Supplier filter
        if ($request->filled('supplier') && $request->supplier !== '') {
            $query->where('supplier_id', $request->supplier);
        }

        return $query;
    }

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
                ->orWhereRaw('LOWER(products.product_condition) LIKE ?', [$like])
                ->orWhereHas('brand', function ($brand) use ($like) {
                    $brand->whereRaw('LOWER(brand_name) LIKE ?', [$like]);
                })
                ->orWhereHas('category', function ($category) use ($like) {
                    $category->whereRaw('LOWER(category_name) LIKE ?', [$like]);
                })
                ->orWhereHas('stock', function ($stock) use ($like) {
                    $stock->whereRaw('LOWER(CAST(stock_quantity AS VARCHAR(50))) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(CAST(price AS VARCHAR(50))) LIKE ?', [$like]);
                })
                ->orWhereHas('supplier', function ($supplier) use ($like) {
                    $supplier->whereRaw('LOWER(company_name) LIKE ?', [$like])
                        ->orWhereRaw('LOWER(supplier_name) LIKE ?', [$like]);
                });
        });
    }

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
            case 'condition_new':
                $query->orderBy('products.product_condition', 'asc');
                break;
            case 'condition_used':
                $query->orderBy('products.product_condition', 'desc');
                break;
            case 'created_desc':
                $query->orderBy('products.created_at', 'desc');
                break;
            case 'created_asc':
                $query->orderBy('products.created_at', 'asc');
                break;
            case 'name_asc':
            default:
                $query->orderBy('products.product_name', 'asc');
                break;
        }

        return $query;
    }

    /**
     * Get base query for POS products - consistent filtering logic
     */
    protected function getPOSProductsQuery()
    {
        return Product::with('brand', 'category', 'stock')
            ->whereHas('stock', function ($query) {
                $query->where('stock_quantity', '>', 0);
            });
    }

    /**
     * Validate product availability for POS operations
     */
    protected function validateProductAvailability(Product $product)
    {
        if (!$product->stock || $product->stock->stock_quantity <= 0) {
            return false;
        }
        return true;
    }

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

        // Get products with pagination (50 per page)
        $products = $query->paginate(50)->withQueryString();

        $suppliers = Suppliers::where('status', 'active')->orderBy('supplier_name')->get();

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('products', 'suppliers'),
            ['currentSort' => $request->get('sort', 'created_desc')]
        );

        // If HTMX request, return only the table partial
        if ($request->header('HX-Request')) {
            return view('partials.productTable_Inventory', $data);
        }

        // Otherwise return full view
        return view('DASHBOARD.inventory', $data);
    }

    public function inventoryList(Request $request)
    {
        $query = Product::with('brand', 'category', 'supplier', 'stock');

        // Apply reusable filters
        $query = $this->applyProductFilters($query, $request);

        // Apply search
        if ($request->filled('search')) {
            $query = $this->applyProductSearch($query, $request->search);
        }

        // Only show products with stock > 0
        $query->whereHas('stock', function ($q) {
            $q->where('stock_quantity', '>', 0);
        });

        $productsCollection = $query->get();

        // Group products
        $grouped = $productsCollection->groupBy(function ($product) {
            return implode('|', [
                $product->product_name,
                $product->brand_id ?? 'null',
                $product->category_id ?? 'null',
                $product->product_condition,
                $product->stock?->price ?? 0,
            ]);
        })->map(function ($group) {
            $first = $group->first();

            // Calculate total quantity from stock_quantity, not product count
            $quantity = $group->sum(function ($product) {
                return $product->stock ? $product->stock->stock_quantity : 0;
            });

            // Determine stock status
            $stock_status = $quantity >= 10 ? 'Good' : 'Low Stock';
            $status_color = $quantity >= 10 ? 'green' : 'red';

            return (object) [
                'id' => $first->id,
                'product_name' => $first->product_name,
                'brand' => $first->brand,
                'category' => $first->category,
                'brand_id' => $first->brand_id,
                'category_id' => $first->category_id,
                'product_condition' => $first->product_condition,
                'quantity' => $quantity, // Real stock quantity
                'price' => $first->stock?->price ?? 0,
                'serial_number' => $first->serial_number ?? 'N/A',
            ];
        })->values();

        // Filter out groups with 0 quantity
        $grouped = $grouped->filter(function ($product) {
            return $product->quantity > 0;
        })->values();

        $sort = $request->get('sort', 'name_asc');
        $productsSorted = match ($sort) {
            'name_desc' => $grouped->sortByDesc('product_name')->values(),
            'qty_desc' => $grouped->sortByDesc('quantity')->values(),
            'qty_asc' => $grouped->sortBy('quantity')->values(),
            'price_desc' => $grouped->sortByDesc('price')->values(),
            'price_asc' => $grouped->sortBy('price')->values(),
            'status_asc' => $grouped->sortBy('stock_status')->values(),
            'status_desc' => $grouped->sortByDesc('stock_status')->values(),
            default => $grouped->sortBy('product_name')->values(),
        };

        // Convert to array for pagination
        $productsArray = $productsSorted->toArray();

        // Manual pagination for collection
        $page = $request->get('page', 1);
        // Inventory List Pagination 
        $perPage = 50;
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($productsArray, $offset, $perPage);

        // Create LengthAwarePaginator instance
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            count($productsArray),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

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
     * Update product price with different logic for Brand New vs Second Hand
     */
    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0'
        ]);

        try {
            $product = Product::with('stock')->findOrFail($id);
            $newPrice = $request->price;
            $oldPrice = $product->stock?->price ?? 0;

            DB::transaction(function () use ($product, $newPrice, $id) {
                if ($product->product_condition === 'Brand New') {
                    // For Brand New: update price for ALL products with same name, brand, category, and condition
                    $productsToUpdate = Product::where('product_name', $product->product_name)
                        ->where('brand_id', $product->brand_id)
                        ->where('category_id', $product->category_id)
                        ->where('product_condition', 'Brand New')
                        ->get();

                    foreach ($productsToUpdate as $prod) {
                        if ($prod->stock) {
                            $prod->stock->price = $newPrice;
                            $prod->stock->save();
                        }
                    }

                    $message = 'Price updated for ' . $productsToUpdate->count() . ' Brand New products.';
                } else {
                    // For Second Hand: update ALL products with same name, brand, category, condition, AND current price
                    $currentPrice = $product->stock->price;
                    $productsToUpdate = Product::where('product_name', $product->product_name)
                        ->where('brand_id', $product->brand_id)
                        ->where('category_id', $product->category_id)
                        ->where('product_condition', 'Second Hand')
                        ->whereHas('stock', function ($query) use ($currentPrice) {
                            $query->where('price', $currentPrice);
                        })
                        ->get();

                    foreach ($productsToUpdate as $prod) {
                        if ($prod->stock) {
                            $prod->stock->price = $newPrice;
                            $prod->stock->save();
                        }
                    }

                    $message = 'Price updated for ' . $productsToUpdate->count() . ' Second Hand products with the same price.';
                }
            });

            // Log the price update
            $description = "Update Price for {$product->product_name}: {$oldPrice} -> {$newPrice}";
            $this->logUpdateAudit('UPDATE', 'Inventory', $description, ['price' => $oldPrice], ['price' => $newPrice], $request);

            return redirect()->route('inventory.list')
                ->with('success');
        } catch (\Exception $e) {
            return redirect()->route('inventory.list')
                ->with('error', 'Failed to update price: ' . $e->getMessage());
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $oldProduct = $product->toArray();
        $oldStock = $product->stock ? $product->stock->toArray() : null;

        DB::transaction(function () use ($product, $data) {
            $productData = collect($data)->except(['price', 'product_condition'])->toArray();
            $productData['serial_number'] = $productData['serial_number'] ?? ($product->serial_number ?? 'N/A');

            // Determine product condition based on supplier_id
            $productData['product_condition'] = (empty($productData['supplier_id']) || $productData['supplier_id'] === null)
                ? 'Second Hand'
                : 'Brand New';

            $product->update($productData);

            $stock = Product_Stocks::firstOrNew(['product_id' => $product->id]);
            $stock->price = $data['price'];
            $stock->stock_quantity = $stock->stock_quantity ?? 1;
            $stock->save();
        });

        // Determine what was updated and log accordingly
        $oldData = $oldProduct;
        $newData = $product->fresh()->toArray();

        // Check which field was actually updated (price or serial_number or other details)
        if (isset($data['price']) && $oldStock && $oldStock['price'] != $data['price']) {
            $condition = 'Price';
            $lastValue = $oldStock['price'];
            $updatedValue = $data['price'];
        } elseif (isset($data['serial_number']) && $oldData['serial_number'] != $data['serial_number']) {
            $condition = 'Serial No.';
            $lastValue = $oldData['serial_number'];
            $updatedValue = $data['serial_number'];
        } else {
            // For other fields, just label as 'Detail'
            $condition = 'Detail';
            $lastValue = json_encode($oldData);
            $updatedValue = json_encode($newData);
        }

        $description = "Update {$condition} for {$product->product_name}: {$lastValue} -> {$updatedValue}";
        $this->logUpdateAudit('Update', 'Inventory', $description, $oldData, $newData, $request);

        return redirect()->route('inventory')->with('success', 'Product updated successfully.');
    }

    public function getRecentProducts(Request $request)
    {
        $search = $request->get('search', '');

        $query = Product::with('brand', 'category', 'stock')
            ->orderBy('created_at', 'desc')
            ->limit(10);

        if (!empty($search)) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }

        $products = $query->get();

        $seen = [];
        $uniqueProducts = [];

        foreach ($products as $product) {
            $productName = $product->product_name;
            $brandId = $product->brand_id ?? 'null';
            $categoryId = $product->category_id ?? 'null';
            $price = $product->stock?->price ?? 0;

            // For Brand New, group by name, brand, category, and price
            // For Second Hand, show individual products
            if ($product->product_condition === 'Brand New') {
                $uniqueKey = md5($productName . '|' . $brandId . '|' . $categoryId . '|' . $price);
            } else {
                $uniqueKey = $product->id; // Each Second Hand product is unique
            }

            if (!isset($seen[$uniqueKey])) {
                $seen[$uniqueKey] = true;
                $uniqueProducts[] = [
                    'id' => $product->id,
                    'product_name' => $productName,
                    'brand_id' => $brandId === 'null' ? null : $brandId,
                    'brand_name' => $product->brand?->brand_name ?? 'N/A',
                    'category_id' => $categoryId === 'null' ? null : $categoryId,
                    'category_name' => $product->category?->category_name ?? 'N/A',
                    'price' => $price,
                    'product_condition' => $product->product_condition,
                ];
            }
        }

        return response()->json(array_slice($uniqueProducts, 0, 10));
    }

    /**
     * Display products for POS system - Show individual products with consistent logic
     */
    public function posList(Request $request)
    {
        // Use consistent base query
        $query = $this->getPOSProductsQuery();

        // Apply reusable filters
        $query = $this->applyProductFilters($query, $request);

        // Apply search
        if ($request->filled('search')) {
            $query = $this->applyProductSearch($query, $request->search);
        }

        // Default sorting by product name
        $query->orderBy('product_name', 'asc');

        // Get individual products - Show all available products with pagination
        $products = $query->paginate(50)->withQueryString();

        $suppliers = Suppliers::where('status', 'active')->orderBy('supplier_name')->get();

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('products', 'suppliers')
        );

        // If HTMX request, return only the product frame partial
        if ($request->header('HX-Request')) {
            return view('POS_SYSTEM.display_productFrame', $data);
        }

        return view('POS_SYSTEM.item_list', $data);
    }

    /**
     * Get product by serial number - Simplified and consistent with posList
     */
    public function getProductBySerialNumber(Request $request)
    {
        $serialNumber = $request->query('serial');

        if (!$serialNumber) {
            return response()->json(['product' => null, 'message' => 'No serial number provided'], 400);
        }

        if (strlen($serialNumber) > 100) {
            return response()->json(['product' => null, 'message' => 'Invalid serial number format'], 400);
        }

        // Find product by serial number using consistent query
        $foundProduct = $this->getPOSProductsQuery()
            ->where('serial_number', $serialNumber)
            ->first();

        if (!$foundProduct) {
            return response()->json(['product' => null, 'message' => 'Serial number not found or product out of stock'], 404);
        }

        // Validate availability using helper method
        if (!$this->validateProductAvailability($foundProduct)) {
            return response()->json(['product' => null, 'message' => 'Product is out of stock'], 404);
        }

        // Simplified: Return actual stock quantity of this specific product
        $price = $foundProduct->stock?->price ?? 0;
        $stockQuantity = $foundProduct->stock?->stock_quantity ?? 0;

        $product = (object) [
            'id' => $foundProduct->id,
            'serial_number' => $foundProduct->serial_number,
            'product_name' => $foundProduct->product_name,
            'brand' => $foundProduct->brand,
            'category' => $foundProduct->category,
            'brand_id' => $foundProduct->brand_id,
            'category_id' => $foundProduct->category_id,
            'product_condition' => $foundProduct->product_condition,
            'image_path' => $foundProduct->image_path,
            'stock' => $stockQuantity,
            'price' => $price,
            'warranty_period' => $foundProduct->warranty_period,
        ];

        return response()->json(['product' => $product, 'message' => 'Product found'], 200);
    }

    public function checkSerialNumber(Request $request)
    {
        $serial = $request->query('serial');

        if (!$serial) {
            return response()->json(['exists' => false]);
        }

        $exists = Product::where('serial_number', $serial)->exists();

        return response()->json(['exists' => $exists]);
    }
}
