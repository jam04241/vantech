<?php

namespace App\Http\Controllers;

use App\Http\Requests\BundleRequest;
use App\Models\Bundles;
use Illuminate\Http\Request;


class BundlesController extends Controller
{
    
    public function create()
    {
        return view('bundles.create');
    }

    public function store(BundleRequest $request)
    {
        $data = $request->validated();
        Bundles::create($data);
        
        return redirect()->route('bundles.index')->with('success', 'Bundle created successfully!');
    }

 
    
}