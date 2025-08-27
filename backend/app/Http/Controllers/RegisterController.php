<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        //procura e empresa e verifica se existe
        $company = Company::where('company_name', $request->company_name)->first();
        if (!$company) {
            $response = [
                'errors' => [
                    'company_name' => ['Nome da empresa incorreto'],
                ],
            ];
            return response()->json($response, 422);
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
            'role' => 'employee', 
        ]); 

        $token = JWTAuth::fromUser($user);
        
        return response()->json([
            'user' => $user,
            'token' => $token,
            'AVISO' => 'Usuário criado e vinculado a empresa ' . $company->company_name . ' com sucesso!',
        ], 201);
    }
}
