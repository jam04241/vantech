<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use app\Http\Requests\CustomerRequest;
use app\Models\Customer;

class CustomerController extends Controller
{

    public function index()
    {
        $Customers = Customer::all();
        return response()->json($Customers);
    }

    public function store(CustomerRequest $request)
    {

        $data = $request->validated();

        Customer::created($data);
        return redirect()->route('product.add')->with('success', 'Category created successfully.');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }
    public function update($id) {}

    public function destroy($id)
    {
        //
    }
}
