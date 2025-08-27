<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $adminTeste = User::where('email', 'adm1@teste.com')->first();
        $funcionarioTeste = User::where('email', 'funcionario@teste.com')->first();

        if ($adminTeste) {
            Task::create([
                'user_id' => $adminTeste->id, 
                'company_id' => $adminTeste->company_id,
                'title' => 'Tarefa de Teste para o Funcionário',
                'description' => 'Esta é uma tarefa de teste criada pelo administrador para todos.',
                'priority' => 'high',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(28),
            ]);
        }

        if ($funcionarioTeste) {
            Task::create([
                'user_id' => $funcionarioTeste->id,
                'company_id' => $funcionarioTeste->company_id,
                'title' => 'Tarefa de Teste para o Admin',
                'description' => 'Esta é uma tarefa de teste criada pelo funcionário para a empresa.',
                'priority' => 'low',
                'status' => 'in progress',
                'due_date' => Carbon::now()->addDays(29),
            ]);
        }
    }
}
