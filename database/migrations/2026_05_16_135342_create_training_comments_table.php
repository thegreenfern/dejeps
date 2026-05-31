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
        Schema::create('training_comments', function (Blueprint $table) {
            $table->id();
            $table->enum('target_type', ['session', 'evaluation']);
            $table->unsignedBigInteger('target_id');
            $table->enum('author', ['instructor', 'trainee']);
            $table->text('body');
            $table->timestamps();

            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_comments');
    }
};
