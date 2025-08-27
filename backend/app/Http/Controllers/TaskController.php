<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\TaskNotificationMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->load('company');
        $tasks = Task::where('company_id', $user->company_id)->paginate(10);

        return response()->json([
            'tasks' => $tasks,
            'user' => $user
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        // Valida os dados da requisição
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in progress,completed,canceled',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        // Verifica se o usuário pertence a mesma empresa da tarefa
        $validated['user_id'] = $user->id;
        $validated['company_id'] = $user->company_id;

        $task = Task::create($validated);

        // Enviar email para todos os funcionários da empresa (será q precisa ser todos?)
        $users = User::where('company_id', $user->company_id)->get();
        foreach ($users as $u) {
            Mail::to($u->email)->send(
                new TaskNotificationMail($task, 'Uma nova tarefa criada!')
            );
        }

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
        $user = JWTAuth::parseToken()->authenticate();

        if ($task->company_id !== $user->company_id) {
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
        $user = JWTAuth::parseToken()->authenticate();

        if ($task->company_id !== $user->company_id) {
            return response()->json(['AVISO' => 'Você não tem permissão para atualizar esta tarefa.'], 403);
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,in progress,completed,canceled',
            'priority' => 'sometimes||in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);
        $task->update($validated);
        $task->refresh();

        // Enviar email para todos se a tarefa for concluida
        if ($task->status === 'completed') {
            $users = User::where('company_id', $user->company_id)->get();
            foreach ($users as $us) {
                Mail::to($us->email)->send(new TaskNotificationMail($task, 'A tarefa foi concluída!'));
            }
        }

        return response()->json(['AVISO' => 'A Tarefa foi atualizada com sucesso!', 'task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($task->company_id !== $user->company_id) {
            return response()->json(['AVISO' => 'Você não tem permissão para excluir esta tarefa'], 403);
        }
        $task->delete();
        return response()->json(['AVISO' => 'A Tarefa foi excluída com sucesso!'], 200);
    }
}
