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
        Schema::create('players', function (Blueprint $table) {
            $table->unsignedBigInteger('player');
            $table->integer('games_played');
            $table->integer('games_won');
            $table->unsignedBigInteger('role');
            $table->tinyInteger('enabled');

            $table->foreign('player')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role')->references('id')->on('hands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
