<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Pega o usuário autenticado via JWT
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['company' => $user->company], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $user = JWTAuth::parseToken()->authenticate();
        $company = Company::find($id);

        if ($company && $company->id === $user->company_id) {
            return response()->json($company, 200);
        } else {
            return response()->json(['AVISO' => 'Você não é funcionário dessa empresa.'], 404);
        }
    }

    /**
     * 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUserCompany()
    {
        //Pega a empresa do usuário autenticado.
        $user = JWTAuth::parseToken()->authenticate();
        
        // Encontra a empresa ou falha com erro 404
        $company = Company::findOrFail($user->company_id);
        
        return response()->json($company);
    }
}
