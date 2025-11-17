<?php

namespace App\Traits;

use App\Models\Brand;

trait LoadsBrandData
{
    /**
     * Retrieve brand and category collections ordered by name.
     *
     * @return array{brands: \Illuminate\Support\Collection, categories: \Illuminate\Support\Collection}
     */
    protected function loadBrands(): array
    {
        $brands = Brand::orderBy('brand_name')->get();
        return compact('brands');
    }
}
