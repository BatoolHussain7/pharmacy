<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'company_name' => 'required|string',
            'email' => 'required|unique:companies,email',
            'password' => 'required',
        ]);

        // Return errors if validation error occur.
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        $company = Company::create([
            'location' => $request->get('location'),
            'company_name' => $request->get('company_name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        if (!$company) {
            // return error
            return response()->json([
                'data' => null,
                'message' => 'Error Register new user',
            ]);
        }


        $token = auth('company-api')->attempt([
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

        $company = Company::where('email', $request->email)->first();

        if (!$company || !Hash::check($request->password, $company->password)) {
            return response()->json([
                'data' => null,
                'message' => 'Credential error',
            ]);
        }
        $company = auth('company-api')->user();
        $token = auth('company-api')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);
        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
