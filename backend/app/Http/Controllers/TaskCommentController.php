<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
    
        $task = Task::where('company_id', $user->company_id)->get();
        $taskID = $task->pluck('id');   //o pluck é usado para extrair apenas os IDs das tarefas da empresa do usuario
        $comment = TaskComment::whereIn('task_id', $taskID)->with('task')->get(); // pega os comentários do usuario autenticado que pertencem as tarefas da empresa dele (*estudar melhor esse with e o whereIn)
        return response()->json($comment, 200);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // pega o usuário autenticado
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string',
        ]);

        $task = Task::find($validated['task_id']); // busca a task

        // verifica se a task existe e se pertence à mesma empresa
        if (!$task || $task->company_id !== Auth::user()->company_id) {
            return response()->json([
                'AVISO' => 'Você não pode comentar em uma tarefa que não pertence à sua empresa.'
            ], 403);
        }
        //Pra criar o comentario
        $comment = new TaskComment();
        $comment->task_id = $validated['task_id'];
        $comment->user_id = Auth::user()->id;
        $comment->comment = $validated['comment']; // aqui usa o mesmo nome que validou
        $comment->save();

        return response()->json(['AVISO'  => 'Comentário criado com sucesso', 'comment' => $comment], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //pega o usuario logado e busca o comentario pelo ID 
        $user = Auth::user();
        $comment = TaskComment::find($id);
        
        if (!$comment || $comment->task->company_id !== $user->company_id) //pra verificar se o comentario pertence a empresa do usuario logado e se o comentario existe
        {
            return response()->json(['Aviso' => 'Comentário não encontrado.'], 404);
        } else {
            return response()->json($comment, 200);
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
        // pega o usuário logado
        $user = Auth::user();

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = TaskComment::find($id);
        if (!$comment) {
            return response()->json(['AVISO' => 'Comentário não encontrado'], 404);
        }
        //  se o comentário é da mesma empresa do usuário
        elseif ($comment->task->company_id !== $user->company_id) {
            return response()->json(['AVISO' => 'Você não tem permissão para atualizar este comentário'], 403);
        } else {
            $comment->comment = $validated['comment'];
            $comment->save();
            return response()->json(['AVISO' => 'Comentário atualizado com sucesso', 'comment' => $comment], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $comment = TaskComment::where('id', $id)
            ->whereHas('task', function ($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })->first(); //pra verificar se o comentario pertence a empresa do usario autenticado (estudar melhor esse whereHas)

        if (!$comment) {
            return response()->json(['AVISO' => 'Comentário não encontrado'], 404);
        } else {
            $comment->delete();
            return response()->json(['AVISO' => 'Comentário deletado com sucesso'], 200);
        }
    }
}
