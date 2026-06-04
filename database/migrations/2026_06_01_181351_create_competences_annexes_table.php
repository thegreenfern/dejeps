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
        Schema::create('competences_annexes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->unique()->constrained('trainees')->cascadeOnDelete();
            $table->unsignedTinyInteger('comp_accueil')->nullable();
            $table->unsignedTinyInteger('comp_gonflage')->nullable();
            $table->unsignedTinyInteger('comp_materiel_securite')->nullable();
            $table->unsignedTinyInteger('comp_bateau')->nullable();
            $table->unsignedTinyInteger('comp_sites')->nullable();
            $table->unsignedTinyInteger('comp_pmt')->nullable();
            $table->unsignedTinyInteger('comp_rangement')->nullable();
            $table->text('notes_formateur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competences_annexes');
    }
};
