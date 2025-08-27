<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company1 = Company::where('company_name', 'Empresa1')->first();
        $company2 = Company::where('company_name', 'Empresa2')->first();

      
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Teste',
            'email' => 'adm1@teste.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'company_id' => $company1->id,
        ]);
        User::create([
            'first_name' => 'Funcionario',
            'last_name' => 'Teste',
            'email' => 'funcionario@teste.com',
            'password' => Hash::make('123456'),
            'role' => 'employee',
            'company_id' => $company1->id,
        ]);
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Exemplo',
            'email' => 'adm2@teste.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'company_id' => $company2->id,
        ]);
        User::create([
            'first_name' => 'Funcionario',
            'last_name' => 'Teste',
            'email' => 'funcionario2@teste.com',
            'password' => Hash::make('123456'),
            'role' => 'employee',
            'company_id' => $company2->id,
        ]);
    }
}
