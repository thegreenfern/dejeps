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
        Schema::create('trainee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained()->cascadeOnDelete();

            // Onboarding progress (1 = profil, 2 = big5, 3 = auto-eval, 4 = done)
            $table->unsignedTinyInteger('onboarding_step')->default(1);
            $table->timestamp('completed_at')->nullable();

            // Step 1 — Profil & ice breaking (free-form answers stored as JSON)
            $table->json('ice_breaking')->nullable();

            // Prior professional experiences (array of {date, duration, location, role, done, learned})
            $table->json('prior_experiences')->nullable();

            // Step 2 — Big Five personality test
            $table->json('big5_answers')->nullable();   // raw answers keyed by question id
            $table->json('big5_scores')->nullable();    // {O, C, E, A, N} each 0–100
            $table->timestamp('big5_completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_profiles');
    }
};
