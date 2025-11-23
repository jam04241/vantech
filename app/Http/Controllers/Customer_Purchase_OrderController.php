<?php

namespace App\Http\Controllers;

use app\http\Models\Customer_purchaseOrdered;
use App\Http\Requests\Customer_purchaseOrderedRequest;
use Illuminate\Http\Request;


use app\Models\Customer_Purchase_Order;

class Customer_Purchase_OrderController extends Controller
{
    public function index()
    {
        $Customer_Purchase_Orders = Customer_Purchase_Order::all();
        return response()->json($Customer_Purchase_Orders);
    }

    public function store($id) {}

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
