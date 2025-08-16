<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Database\Seeder;
use App\Models\User;


class TaskCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskComment::factory()->count(20)->create();
    }
}
