<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id, // associar o comentario a um usuario aleatorio
            'task_id' => Task::all()->random()->id, 
            'comment' => $this->faker->paragraph, 
        ];
    }
}
