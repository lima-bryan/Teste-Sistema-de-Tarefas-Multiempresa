<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          User::factory()->count(20)->create();

        //usuario para teste
        User::create([
            'company_id' => 1,
            'first_name' => 'Bryan',
            'last_name' => 'Lima',
            'email' => 'admin@email.com',
            'phone' => '11999999999',
            'password' => Hash::make('12345678'), //senha criptografada
            'email_verified_at' => now(), //pra vereficar a data de verificação do email
        ]);
        User::create([
            'company_id' => 1,
            'first_name' => 'Marcos',
            'last_name' => 'Lima',
            'email' => 'admin2@email.com',
            'phone' => '11999999999',
            'password' => Hash::make('12345678'), //senha criptografada
            'email_verified_at' => now(), //pra vereficar a data de verificação do email
        ]);
    }
}
