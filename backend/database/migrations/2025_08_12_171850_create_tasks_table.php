<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId ('company_id') -> constrained() -> ondDelete('cascade') -> onUpdate('cascade');
            $table -> foreignId ('user_id') -> constrained() -> ondDelete('cascade') -> onUpdate('cascade');
            $table -> string('title');
            $table -> text('description') -> nullable();
            $table -> enum ('status', ['pending', 'in_progress', 'completed', 'cancelled']) -> default('pending');
            $table -> dateTime('due_date') -> nullable();
            $table -> dateTime('completed_at') -> nullable();
            $table -> dateTime('cancelled_at') -> nullable();
            $table -> string('priority') -> default('medium'); // aqui fica os niveis de prioridade (low, medium e high) das tasks
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
