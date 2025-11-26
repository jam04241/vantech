<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Requests\PaymentMethodRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Payment_MethodController extends Controller
{
   
    
    public function store(PaymentMethodRequest $request)
    {
        $data = $request->validated();
        PaymentMethod::create($data);
    }
}
