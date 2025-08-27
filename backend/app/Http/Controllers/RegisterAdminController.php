<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class RegisterAdminController extends Controller
{
    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        /* valida todos os campos do formulario antes de qualquer operação no banco de dados
         Se a validação falhar, o processo para aqui com o erro 422*/
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|string|max:255|unique:companies,company_name',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // bloco try catch para tratar erros inesperados.
        try {
            DB::transaction(function () use ($request, &$user, &$company) {
                $company = Company::create([
                    'company_name' => $request->company_name,
                    'phone' => $request->phone,
                ]);
                $userRole = 'admin';

                // aqui cria o adm e vincula a empresa criada
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_id' => $company->id,
                    'role' => $userRole,
                ]);
            });

            // Se der tudo certo em cima vem pra ca e gera token para o novo usuário.
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'user' => $user,
                'company' => $company,
                'token' => $token,
                'message' => 'Empresa e usuário administrador criados com sucesso!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha no registro. Por favor, tente novamente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
