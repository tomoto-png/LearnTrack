<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_group_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('category_group_id')->references('id')->on('category_groups')->onDelete('cascade');
        });
        DB::table('categories')->insert([
            ['name' => '国語', 'category_group_id' => 1],
            ['name' => '数学', 'category_group_id' => 1],
            ['name' => '英語', 'category_group_id' => 1],
            ['name' => '理科', 'category_group_id' => 1],
            ['name' => '社会', 'category_group_id' => 1],
            ['name' => 'その他', 'category_group_id' => 1],

            ['name' => '理工系', 'category_group_id' => 2],
            ['name' => '社会科学系', 'category_group_id' => 2],
            ['name' => '人文科学系', 'category_group_id' => 2],
            ['name' => '医学系', 'category_group_id' => 2],
            ['name' => '芸術系', 'category_group_id' => 2],
            ['name' => '教育系', 'category_group_id' => 2],
            ['name' => 'ビジネス系', 'category_group_id' => 2],
            ['name' => '環境・自然科学系', 'category_group_id' => 2],
            ['name' => '情報科学系', 'category_group_id' => 2],
            ['name' => '法律系', 'category_group_id' => 2],
            ['name' => 'その他', 'category_group_id' => 2],

            ['name' => '英検', 'category_group_id' => 3],
            ['name' => 'TOEIC', 'category_group_id' => 3],
            ['name' => '漢検', 'category_group_id' => 3],
            ['name' => '大学受験', 'category_group_id' => 3],
            ['name' => '高校受験', 'category_group_id' => 3],
            ['name' => '資格試験', 'category_group_id' => 3],
            ['name' => 'その他', 'category_group_id' => 3],

            ['name' => 'モチベーション', 'category_group_id' => 4],
            ['name' => '時間管理', 'category_group_id' => 4],
            ['name' => '集中力', 'category_group_id' => 4],
            ['name' => '勉強法', 'category_group_id' => 4],
            ['name' => 'その他', 'category_group_id' => 4],

            ['name' => '雑談', 'category_group_id' => 5],
            ['name' => 'その他の学習', 'category_group_id' => 5],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
