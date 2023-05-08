<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Info;
use Illuminate\Support\Facades\Auth;
use App\Models\PharmacyProduct;
use App\Models\Product;

class AdminController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pharmacist_name' => 'required|string',
            'email' => 'required|unique:admins,email',
            'password' => 'required',
            //   'networth' => 'required ',
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $admin = Admin::create([
            'pharmacist_name' => $request->get('pharmacist_name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        if (!$admin) {
            // return error
            return response()->json([
                'data' => null,
                'message' => 'Error Register new user',
            ]);
        }


        $token = auth('admin-api')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'data' => null,
                'message' => 'Credential error',
            ]);
        }
        $admin = auth('admin-api')->user();
        $token = auth('admin-api')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);
        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function AddProduct(Request $request)
    {
        if (auth('admin-api')) {
            $product = Product::where('product_name', $request->name)->first();
            if ($product) {
                $produt_pharmacy = PharmacyProduct::create([
                    'product_id' => $product->id,
                    'pharmacy_id' => auth('admin-api')->user()->id,
                    'customer_net' => $product->customer_net,
                ]);
                return response()->json([
                    "message" => "product add",
                    "product" => $product
                ]);
            } else {
                return response()->json(["message" => "product not found"]);
            }
        } else
            return response()->json(["not authorized"]);
    }
    public function AddInfo(Request $request, $product_id)
    {
        if (!auth('admin-api')) {
            return response()->json(['message' => 'not authorized']);
        } else {
            $pharmacy_product = PharmacyProduct::where('product_id', $product_id)
                ->where('pharmacy_id', auth('admin-api')->user()->id)->first();
        }
        $validator = validator::make(
            $request->all(),
            [
                'quantity' => 'required|integer',
                'expiration_date' => 'required|date'
            ]

        );
        if ($validator->fails()) {
            $error = $validator->errors();
            return response()->json([$error]);
        }
        $info = new Info;
        $info->quantity = $request->quantity;
        $info->expiration_date = $request->expiration_date;
        $info->pharmacy_product_id = $pharmacy_product->id;
        $info->save();
        return response()->json([
            "message" => "added successfully", "info" => $info
        ]);
    }
    public function totalAmount($product_id)
    {
        $pharmacy_product = PharmacyProduct::where('product_id', $product_id)
            ->where('pharmacy_id', auth('admin-api')->user()->id)->first();
        $infos = Info::where('pharmacy_product_id', $pharmacy_product->id)->get();
        $totalamount = 0;
        foreach ($infos as $info) {
            $totalamount = $info->quantity + $totalamount;
        }
        $pharmacy_product->total_amount = $totalamount;
        $pharmacy_product->save();
        return response()->json([$totalamount]);
    }
    public function createBill(Request $request)
    {
        $rules = [
            'product_id' => 'array|required',
            'quantity' => 'array|required'
        ];
        $validator = Validator::make(
            $request->only('product_id'),
            $rules
        );
        $pharmacy_id = auth('admin-api')->user()->id;
        $ids = $request->product_id;
        $totalprice = 0;
        foreach ($ids as $id) {
            $pharmacy_product = PharmacyProduct::where('product_id', $id)
                ->where('pharmacy_id', $pharmacy_id)->first();
            $totalprice = $pharmacy_product->customer_net + $totalprice;
        }
    }
}
