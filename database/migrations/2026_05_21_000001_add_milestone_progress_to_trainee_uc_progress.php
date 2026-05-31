<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainee_uc_progress', function (Blueprint $table) {
            $table->json('milestone_progress')->nullable()->after('instructor_notes');
        });
    }

    public function down(): void
    {
        Schema::table('trainee_uc_progress', function (Blueprint $table) {
            $table->dropColumn('milestone_progress');
        });
    }
};
