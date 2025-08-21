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
            $table -> foreignId ('company_id') -> constrained() -> onDelete('cascade') -> onUpdate('cascade');
            $table -> foreignId ('user_id') -> constrained() -> onDelete('cascade') -> onUpdate('cascade');

            $table -> string('title', 100);
            $table -> text('description') -> nullable();
            $table -> enum ('status', ['pending', 'in progress', 'completed' ,'canceled']) -> default('pending');
            $table -> enum('priority', ['low', 'medium', 'high'])-> default('low'); 

            $table -> dateTime('due_date') ;
            $table -> dateTime('completed_at') -> nullable();
            $table -> dateTime('cancelled_at') -> nullable();
            
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
