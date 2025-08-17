<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //variavel de status que vai receber um status aleatorio e para preencher o completed_at e cancelled_at
        $status = $this->faker->randomElement(['pending', 'in_progress', 'completed', 'canceled']);

        return [
            'user_id' => User::all()->random()->id, // a mesma coisa q a task de cima, associar...
            'company_id' => User::all()->random()->company_id, // associar a tarefa a uma empresa aleatoria
            'title' => Str::limit($this->faker->sentence, 100), 
            
            
            'description' => $this->faker->paragraph,

            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'canceled']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            
            'due_date' => now(),
            'completed_at' =>  $status === 'concluida' ? now() : null, // só vai preencher se o status for concluída
            'cancelled_at' => $status === 'cancelada' ? now() : null,  //     ...                          cancelada 


        ];
    }
}
