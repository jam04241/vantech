<?php

namespace App\Http\Controllers;

use App\Models\CustomerPurchaseOrder;
use App\Models\Product;
use App\Http\Requests\Customer_purchaseOrderedRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Customer_Purchase_OrderController extends Controller
{


    public function store(Customer_purchaseOrderedRequest $request)
     {
         $data = $request->validated();
         CustomerPurchaseOrder::create($data);
     }
    
        

}
