<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        return response()->json(['company' => $user->company], 200); //retornar a empresa do usuario logado
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
     /*
     public function store(Request $request)
    {
        //como n tem adm n precisater o store
    }
     */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user= Auth::user();
        $company = Company::find($id); //pra procurar a empressa pelo id dela
        if ($company && $company->id === $user->company_id) {
            return response()->json($company, 200); //retorna a empresa do usuario logado
        } else {
            return response()->json(['AVISO' => 'Você não é funcionario dessa empresa.'], 404);
        }
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
        $user = Auth::user();
        $company = Company::find($id);
        //obs1.: n existe uma regra de negocio pra atualizar o nome das empresas pois as empresas n tem adm ou cargos 
        if (!$company || $company->id !== $user->company_id) {
            return response()->json(['AVISO' => 'Você não tem permissão para atualizar informações dessa Empresa.'], 403);
        }
        
        //obs2.: por isso que aqui eu só permito atualizar o endereço e telefone da empresa
        $validated = $request->validate([ 
            'address' => 'sometimes|string',
            'phone' => 'sometimes|string',]);
        
        $company->update($validated);
        return response()->json(['AVISO' => 'Informações da Empresa atualizada com sucesso!', 'company' => $company], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

   /*
    public function destroy($id)
    {
  //como n tem adm n precisater o destroy
    }
   */

}

