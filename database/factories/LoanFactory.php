<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'loan_amount'=>fake()->sentence(),
             'loan_description'=>fake()->sentence(),
             'loan_repayment_date'=>'12-12-2022',
             'application_status'=>'PENDING'
        ];
    }
}
