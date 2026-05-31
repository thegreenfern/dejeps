<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_uc_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->enum('uc', ['uc1', 'uc2']);
            $table->text('dossier_url')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'submitted', 'evaluated'])
                  ->default('not_started');
            $table->enum('rating', ['TI', 'I', 'S', 'M'])->nullable();
            $table->text('instructor_notes')->nullable();
            $table->unique(['trainee_id', 'uc']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_uc_progress');
    }
};
