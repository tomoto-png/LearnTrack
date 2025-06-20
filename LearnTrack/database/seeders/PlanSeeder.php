<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'user_id' => 1,
                'name' => 'Laravel 入門',
                'description' => 'Laravelの基本的なルーティングやコントローラーを学ぶ',
                'target_hours' => 10.0,
                'priority' => 'high',
                'progress' => 3.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'SQL の復習',
                'description' => 'JOINやGROUP BYの使い方を復習する',
                'target_hours' => 5.0,
                'priority' => 'medium',
                'progress' => 1.0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
