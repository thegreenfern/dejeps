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
        Schema::create('initial_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('competency_id')->constrained()->cascadeOnDelete();

            // Trainee self-assessment (1 = no notion, 2 = with help, 3 = autonomous)
            $table->unsignedTinyInteger('trainee_score')->nullable();
            $table->text('trainee_evidence')->nullable(); // required when score = 3

            // Tutor counter-evaluation
            $table->unsignedTinyInteger('tutor_score')->nullable();
            $table->text('tutor_notes')->nullable();

            // Planned hours for this competency area
            $table->unsignedSmallInteger('hours_target')->nullable();

            $table->unique(['trainee_id', 'competency_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_assessments');
    }
};
