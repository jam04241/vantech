<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
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
        $data = array_merge(
            $this->loadBrands(),
            $this->loadCategories(),
            $this->loadProducts(),
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

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
