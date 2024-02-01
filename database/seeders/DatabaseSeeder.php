<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\LoanApplication;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'firstname' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // LoanApplication::factory(10)->create();


        $this->call([
            ApplicationTypeSeeder::class,
            UserSeeder::class
        ]);

    }
}
