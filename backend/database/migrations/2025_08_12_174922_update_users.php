<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->renameColumn('name', 'first_name');
            $table->enum('role', ['admin', 'user', 'manager'])->default('user'); // aqui fica os niveis de permissao (admin, manager e user)


            $table->foreignId('company_id')->nullable()->constrained()
                ->onDelete('set null')->onUpdate('cascade'); // relaciona a tabela users com a tabela companies

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()

    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
            $table->dropColumn('role');
            $table->dropColumn('phone');
            $table->renameColumn('first_name', 'name');
        });
    }
}
