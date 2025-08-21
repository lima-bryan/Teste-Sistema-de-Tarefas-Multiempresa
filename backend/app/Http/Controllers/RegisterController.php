<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Company;
use Illuminate\Validation\Rule;


class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //validar todos os dados
        $validator = validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'company_name' => ['required', 'string', 'max:150', Rule::unique('companies', 'name')], //pra garantir que tenha apenas uma empresa por nome
            ],
            // mensagem do erro 422
            ['company_name.unique' => 'Empresa já cadastrada com esse nome!'],
        );
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); //
        }
        //criar empresa
        $company = Company::create([
            'name' => $request->company_name,
            'phone' => $request->phone,
            'address' => $request->address,

        ]);
        //criar usuario vinculado a empresa
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,

        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json([
            'user' => $user,
            'company' => $company,
            'token' => $token,
            'type' => 'bearer',
            'AVISO' => 'Usuário e Empresa cadastrados com sucesso!',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
