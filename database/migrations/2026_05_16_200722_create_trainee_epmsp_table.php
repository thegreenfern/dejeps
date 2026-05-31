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
        Schema::create('trainee_epmsp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['25m', 'pedagogie']);
            $table->enum('status', ['not_started', 'in_progress', 'ready', 'evaluated'])->default('not_started');
            $table->enum('certification', ['favorable', 'defavorable'])->nullable();
            $table->json('ratings')->nullable();
            $table->text('instructor_notes')->nullable();
            $table->unique(['trainee_id', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_epmsp');
    }
};
