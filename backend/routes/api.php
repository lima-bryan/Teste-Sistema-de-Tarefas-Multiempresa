<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* USAR PRA TESTE
use App\Models\User;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Company;
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ROUTES PARA TESTES
/*
Route::get ('/company', function(){
    $company = User::all(); //Tem como usar first, find ou where e algumas outras funções

     //se exister usario ele é retornado. 
    if ($company){
        return $company;
    } else {
        return response()->json(['AVISO' => 'Nenhuma empresa encontrada!'], 404);
    }
});

Route::get('/user-test', function () {
    $user = User::all(); 


    if ($user){
        return $user;
    } else {
        return response()->json(['AVISO' => 'Nenhum usuario encontrado!'], 404);
    }
});

Route::get('/tasks', function (){
    $task = Task::all();

    if ($task){
        return $task;
    } else {
        return response () ->json (['AVISO' => 'Nenhuma tarefa encontrada!'], 404);
    }
});

Route::get('/task-comment', function (){
    $taskComent= TaskComment::all();

    if ($taskComent){
        return $taskComent;
    } else {
        return response ()->json (['AVISO' => 'Nenhum comentário encontrado!'], 404);
    }
});
*/


// Rota de login pública - view (endpoint)
Route::post('/login', [AuthController::class, 'login']);

// middleware para autenticação de usuario (o front sabe quem ta logado por aqui)
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //Route de recursos API (API Resource )  - CRUD
    Route::apiResource('companies', CompanyController::class); 
    Route::apiResource('users', UserController::class); 
    Route::apiResource('tasks', TaskController::class); 
    Route::apiResource('task-comments', TaskCommentController::class);  
});