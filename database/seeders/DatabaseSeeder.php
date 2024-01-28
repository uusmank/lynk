<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'user_type' => 'admin',
        ]);

        \App\Models\User::factory()->create([
            'email' => 'customer@example.com',
            'user_type' => 'customer',
        ]);
        \App\Models\Transaction::factory(20)->create();
        \App\Models\Payment::factory(50)->create();

    }
}
