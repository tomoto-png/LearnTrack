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
        Schema::create('study_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id')->nullable(); // NULLを許容
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration')->comment('Duration in minutes'); 
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('set null'); // set null に変更
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_sessions');
    }
};
