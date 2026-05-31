<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_uc3', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['not_started', 'in_progress', 'ready', 'evaluated'])->default('not_started');
            $table->string('subject', 500)->nullable();
            $table->json('ratings')->nullable();
            $table->text('session_notes')->nullable();
            $table->text('interview_notes')->nullable();
            $table->unique('trainee_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_uc3');
    }
};
