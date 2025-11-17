<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\LoadsCategoryData;

class CategoryController extends Controller
{
    use LoadsCategoryData;

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        Category::create($validated);
        return redirect()->route('product.add')->with('success', 'Category created successfully.');
    }
    public function posCategories()
    {
        return view('POS_SYSTEM.sidebar.app', $this->loadCategories());
    }

    public function inventorygetCategories()
    {
        return response()->json($this->loadCategories()['categories']);
    }

    public function inventoryListgetCategories()
    {
        return response()->json($this->loadCategories()['categories']);
    }

    public function categoryHistory()
    {
        return view('INVENTORY.brandcategoryHistory', $this->loadCategories());
    }


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
