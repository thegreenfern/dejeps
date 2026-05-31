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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->date('session_date');
            $table->string('location', 200)->default('');
            $table->string('theme', 500)->default('');
            $table->text('notes')->nullable();

            // Progression phase of this session
            $table->enum('phase', [
                'observation',
                'supervision_directe',
                'supervision_indirecte',
                'autonome',
            ])->default('observation');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
