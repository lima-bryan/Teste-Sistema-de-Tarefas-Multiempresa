<?php

namespace Database\Seeders;

use App\Models\Company;

use Illuminate\Database\Seeder;


class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'company_name' => 'Empresa1',
            'phone' => '5511987654321',
        ]);

        Company::create([
            'company_name' => 'Empresa2',
            'phone' => '5521912345678',
        ]);
    }
}