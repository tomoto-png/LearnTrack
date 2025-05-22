<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
            [
                'user_id' => 1,
                'content' => 'LaravelのEloquentでリレーションの定義方法がよく分かりません。hasManyとbelongsToの違いを教えてください。',
                'image_url' => null,
                'auto_repost_enabled' => false,
                'reward' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 1,
                'content' => 'Dockerでコンテナ間通信をするときのネットワーク設定がうまくいきません。どう設定すればよいですか？',
                'image_url' => null,
                'auto_repost_enabled' => true,
                'reward' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'content' => 'SQLで複数の条件を組み合わせた検索（AND/OR）の書き方が分からないので教えてください。',
                'image_url' => null,
                'auto_repost_enabled' => false,
                'reward' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
