<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*  Pra testar
         $user = Auth::user();
         $tasks = Task::where('company_id', $user->company_id)->get();
         return response()->json($tasks);
         */

        // Retorna apenas as tarefas q pertecem ao usuario logado
        return Task::where('company_id', Auth::user()->company_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida os dados da requisição
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in progress,completed,canceled',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);
        
        // Adiciona o user_id e company_id do usuário logado
        $validated['user_id'] = Auth::user()->id;
        $validated['company_id'] = Auth::user()->company_id; //

        $task = Task::create($validated);
        return response()->json(['AVISO' => 'Nova tarefa criada com Sucesso!', 'task' => $task], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //Mostrar as tarefas do usuario logado

        if($task->company_id !==Auth::user()->company_id){
            return response()->json(['AVISO' => 'Você não tem permissão para ver esta tarefa'], 403);
        } else {
            return response()->json($task);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
        if ($task->company_id !== Auth::user()->company_id){
            return response()->json(['AVISO' => 'Você não tem permissão para atualizar esta tarefa'], 403);
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending, in progress,completed,canceled',
            'priority' => 'sometimes||in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);
         $task ->update($validated);
         return response()->json([ 'AVISO' => 'Tarefa atualizada com sucesso!', 'task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if($task->company_id !== Auth::user()->company_id){
            return response()->json(['AVISO' => 'Você não tem permissão para excluir esta tarefa'], 403);
        }
        $task->delete();
        return response()->json(['AVISO' => 'Tarefa excluída com sucesso!'], 200);
    }
}
