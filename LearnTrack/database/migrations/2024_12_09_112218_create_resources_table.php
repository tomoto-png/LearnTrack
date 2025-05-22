<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // インデックス追加
            $table->string("title", 255); // 最大長を明示
            $table->string("url")->nullable(); // 通常のURL長で対応
            $table->enum('type', ['1', '2', '3']);
            $table->boolean('visibility')->default(false);
            $table->longText("description"); // 長い説明文も対応可能に
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
