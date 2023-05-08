<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function AddProduct(Request $request)
    {
        if (!auth('company-api')) {
            return response()->json(
                ["not authorized"]
            );
        } else {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string',
                'pharmacist_net' => 'required|integer',
                //   'customer_net' => 'required|integer',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'error' => $errors
                ], 400);
            }

            $product = Product::create([
                'product_name' => $request->get('product_name'),
                'pharmacist_net' => $request->get('pharmacist_net'),
                'customer_net' => $request->pharmacist_net + ($request->pharmacist_net * 10 / 100),
                'company_id' => auth('company-api')->user()->id
            ]);

            if (!$product) {
                // return error
                return response()->json([
                    'data' => null,
                    'message' => 'Error Register new user',
                ]);
            }
            return response()->json([
                'product' => $product,
                'message' => 'added successfully'
            ]);
            
        }
    }

  
}
