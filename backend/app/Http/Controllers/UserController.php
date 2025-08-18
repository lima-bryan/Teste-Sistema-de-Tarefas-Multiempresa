<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * endpoint: get
     */
    public function index()
    {
        return User::where('company_id', Auth::user()->company_id)->paginate(5); //retorna todos os usuarios da empresa do usuario autenticado, pagina 5 por vez, 
        // ->get();         para retornar todos os usuarios
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * endoint: post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            //se nao colocar | da erro no banco pq o laravel vai reconhecer como uma unica regra
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:users', //valida o email se for unico
            'password' => 'required|string|min:8|confirmed' //valida a senha se for maior que 8 caracteres
        ]);
        $validated['password'] = Hash::make($validated['password']); //criptografando a senha
        $validated['company_id'] = Auth::user()->company_id; //associa o usuario a empresa do usuario autenticado
        $user = User::create($validated);
        return response()->json($user, 201);  //

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * endpoint get 
     * 
     */
    public function show(User $user)
    {
        if ($user->company_id !== Auth::user()->company_id) {
            return response()->json(['AVISO' => 'Usuário não encontrado.'], 404);
        } else{
            return response()->json($user); 
        }  
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * endpoint:put/patch *
     */
    public function update(Request $request, $user)
    {
        if ($user->company_id !== Auth::user()->company_id) {
            return response()->json(['AVISO' => 'Usuário não encontrado!'], 404);
        }
         //valida os dados do usuario
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable string min:8 confirmed', ]); 

        if ($request->filled('password'))  //aqui eu verifico se a senha foi preenchida
        {
            $user->password = Hash::make($validated['password']);
        }
        $user->update($validated);
        return response()->json(['AVISO' => 'Usuario atualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->company_id !== Auth::user()->company_id) {
            return response()->json(['AVISO' => 'Usuário não encontrado!'], 404);
        }
        $user->delete();
        return response()->json(['AVISO' => 'Usuário deletado com sucesso!'], 200);
    }
}
