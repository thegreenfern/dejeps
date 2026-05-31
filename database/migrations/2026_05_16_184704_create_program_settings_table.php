<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_settings', function (Blueprint $table) {
            $table->id();

            // UC1/UC2 key dates
            $table->date('uc1_submission_deadline')->nullable();
            $table->date('uc1_jury_date')->nullable();
            $table->date('uc2_submission_deadline')->nullable();
            $table->date('uc2_jury_date')->nullable();

            // Dive center info (shared for all trainees)
            $table->string('dc_name')->nullable();
            $table->string('dc_address')->nullable();
            $table->string('dc_type')->nullable();          // club / commercial / CREPS / autre
            $table->string('dc_director')->nullable();
            $table->string('dc_email')->nullable();
            $table->string('dc_phone')->nullable();
            $table->text('dc_description')->nullable();
            $table->text('dc_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_settings');
    }
};
