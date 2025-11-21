<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Traits\LoadsBrandData;
use App\Traits\LoadsProductData;
use App\Traits\LoadsCategoryData;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use LoadsBrandData;
    use LoadsProductData;
    use LoadsCategoryData;

    public function index()
    {
        $brands = Brand::all();
        return response()->json($brands);
    }

    public function posBrand()
    {
        // Get all products with their stock relationships
        $productsCollection = Product::with('brand', 'category', 'stock')->get();

        // Map individual products with their serial numbers
        // Each product is displayed individually so any serial can be scanned
        $grouped = $productsCollection->map(function ($product) {
            return (object) [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'serial_number' => $product->serial_number, // Each product has its own serial
                'brand' => $product->brand,
                'category' => $product->category,
                'brand_id' => $product->brand_id,
                'category_id' => $product->category_id,
                'product_condition' => $product->product_condition,
                'image_path' => $product->image_path,
                'stock' => 1, // Each individual product = 1 unit
                'price' => $product->stock?->price ?? 0,
            ];
        })->values();

        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            compact('grouped'),
        );

        return view('POS_SYSTEM.item_list',  $data);
    }

    public function inventoryBrand()
    {
        $data = array_merge(
            $this->loadBrands(),
            $this->loadProducts()
        );
        return view('DASHBOARD.inventory', $data);
    }

    public function inventoryListBrand()
    {
        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            $this->loadGroupedProducts(), // Use grouped products to sum quantities by product name
        );
        return view('DASHBOARD.inventory_list', $data);
    }

    public function brandHistory()
    {
        return view('INVENTORY.brandcategoryHistory', $this->loadBrands());
    }

    public function store(BrandRequest $request)
    {
        $validated = $request->validated();
        Brand::create($validated);
        return redirect()->route('product.add')->with('success', 'Brand created successfully.');
    }

    public function create() {}


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(BrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        return redirect()->route('inventory')->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        //
    }
}
