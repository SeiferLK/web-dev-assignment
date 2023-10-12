<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable Laravel Scout indexing during database seeding.
        config(['scout.driver' => 'null']);

        User::factory()->create([
            'name' => "Ash",
            'email' => 'ash@gmail.com',
            'password' => bcrypt('password'),
        ]);

        Author::factory()
            ->count(50)
            ->hasBooks(1)
            ->create();

        Author::factory()
            ->count(100)
            ->hasBooks(2)
            ->create();

        Author::factory()
            ->count(150)
            ->hasBooks(3)
            ->create();
    }
}
