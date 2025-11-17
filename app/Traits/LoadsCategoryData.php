<?php

namespace App\Traits;

use App\Models\Category;

trait LoadsCategoryData
{
    /**
     * Retrieve products with their related brand, category, and supplier data.
     *
     * @return array{categories: \Illuminate\Support\Collection}
     */
    protected function loadCategories(): array
    {
        $categories = Category::orderBy('category_name')->get();
        return compact('categories');
    }
}
