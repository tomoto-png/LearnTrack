<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            PlanSeeder::class,
            StudySessionSeeder::class,
            CategoryGroupSeeder::class,
            CategorySeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
