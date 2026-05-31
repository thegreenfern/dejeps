<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainee_uc3', function (Blueprint $table) {
            $table->json('peda_timeline_overrides')->nullable()->after('trainee_topic_progress');
        });
    }

    public function down(): void
    {
        Schema::table('trainee_uc3', function (Blueprint $table) {
            $table->dropColumn('peda_timeline_overrides');
        });
    }
};
