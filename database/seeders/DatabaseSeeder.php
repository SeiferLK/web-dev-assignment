<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => "Ash",
            'email' => 'ash@gmail.com',
            'password' => bcrypt('password'),
        ]);

        Author::factory()
            ->count(5)
            ->hasBooks(1)
            ->create();

        Author::factory()
            ->count(10)
            ->hasBooks(2)
            ->create();


        Author::factory()
            ->count(15)
            ->hasBooks(3)
            ->create();
    }
}
