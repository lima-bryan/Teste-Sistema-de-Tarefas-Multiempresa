<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RegisterAdminController;


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

// Rotas de autenticação (não protegidas)
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/register-admin', [RegisterAdminController::class, 'store']);

// Rotas protegidas (exigem autenticação JWT)
Route::middleware('auth:api')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    
    // Rota para pegar a empresa do usuário autenticado
    Route::get('/my-company', [CompanyController::class, 'showUserCompany']);

    // Rota para pegar dados do usuário autenticado
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    // Rotas de recursos API (usuário autenticado pode acessar)
    Route::apiResource('companies', CompanyController::class)->except(['store', 'update']); // Apenas para visualização
    Route::apiResource('users', UserController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('task-comments', TaskCommentController::class);
});
