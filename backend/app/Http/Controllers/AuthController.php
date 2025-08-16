<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password'); //validando a credencial


        $token = auth('api')->attempt($credentials); // aqui tenta autenticar
 
        /*                 IMPORTANTE!!
         SE TIVER UM MUTADOR NO MODELS DE USER NA HORA DO TESTE VAI SEMPRE PARAR NO IF PQ O MUTADOR ALTERA A SENHA
        */

         if (!$token) {
        return response()->json(['AVISO' => 'Credenciais inválidas'], 401);
    }
        //se funcionar em cima ele pega (la ele) o usuario autenticado aqui
        $user = Auth('api')->user();

        return response()->json([
            'user' => $user,  //aqui estao os dados do usuario autenticado, nome e etc, por isso n preciso  pegas as ouras informaççoes, telefone e etc o laravel já faz isso
            'company_id' => $user->company_id, //aqui eu pego id da empresa do user autenticado (é importante para o front)
            'type' => 'bearer',

            'token' => $token, //retornando o token

            'AVISO' => 'Login realizado com sucesso!',
        ]);
    }
}
