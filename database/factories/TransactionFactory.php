<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'payer_id' => \App\Models\User::inRandomOrder()->first()->id,
            'due_on' => $this->faker->date(),
            'vat' => $this->faker->randomFloat(2, 5, 20),
            'is_vat_inclusive' => $this->faker->boolean,
        ];
    }
}
