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
        Schema::create('direction_plongee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('trainees')->cascadeOnDelete();
            $table->date('evaluated_at');
            $table->string('status')->default('en_cours');
            $table->unsignedTinyInteger('comp_orientation')->nullable();
            $table->unsignedTinyInteger('comp_gestion_group')->nullable();
            $table->unsignedTinyInteger('comp_chaine_secours')->nullable();
            $table->unsignedTinyInteger('comp_remontee_assist')->nullable();
            $table->unsignedTinyInteger('comp_gestion_env')->nullable();
            $table->unsignedTinyInteger('comp_materiel')->nullable();
            $table->decimal('note_globale', 3, 2)->nullable();
            $table->text('instructor_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direction_plongee_evaluations');
    }
};
