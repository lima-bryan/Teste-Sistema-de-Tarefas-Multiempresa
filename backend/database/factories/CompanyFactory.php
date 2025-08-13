<?php

namespace Database\Factories;

use App\Models\Company;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
            //limitei todos os carcteeres de acordo com a minha tabela para n ter erro na hora de gerar a empresa

            'name' => Str::limit($this->faker->unique()->company, 20), 
            'email' => Str::limit( $this->faker->unique()->companyEmail, 100),
            'phone' => Str::limit($this->faker->phoneNumber(), 20),
            'address' => Str::limit( $this->faker->address, 100),

        ];
    }
}
