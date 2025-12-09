<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\LoadsCategoryData;
use App\Traits\LogsAuditTrail;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use LoadsCategoryData;
    use LogsAuditTrail;

    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $category = Category::create($validated);

        // Log the category creation
        $description = "Added a new Category {$validated['category_name']}";
        $this->logCreateAudit('CREATE', 'Inventory', $description, $validated, $request);

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
    public function update(CategoryRequest $request, Category $category)
    {
        $oldData = $category->toArray();
        $validated = $request->validated();
        $category->update($validated);

        // Log the category update
        $description = "Update {$oldData['category_name']} ->{$validated['category_name']}";
        $this->logUpdateAudit('UPDATE', 'Inventory', $description, $oldData, $validated, $request);

        return redirect()->route('inventory')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        //
    }
}
