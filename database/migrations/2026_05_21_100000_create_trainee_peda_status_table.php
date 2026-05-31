<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_peda_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['bapteme', 'n1', 'n2', 'n3']);
            $table->enum('status', ['nt', 'observation', 'supervision_directe', 'supervision_indirecte', 'autonomie'])
                  ->default('nt');
            $table->boolean('is_manual')->default(false);
            $table->timestamps();

            $table->unique(['trainee_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_peda_status');
    }
};
