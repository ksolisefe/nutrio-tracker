<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->command->info('Importing USDA Foundation Foods...');
        $this->command->call('app:import-usda-data');

        $this->command->info('Importing USDA Branded Foods...');
        $this->command->call('app:import-usda-branded-data');

        $this->command->info('Database seeding completed!');
    }
}
