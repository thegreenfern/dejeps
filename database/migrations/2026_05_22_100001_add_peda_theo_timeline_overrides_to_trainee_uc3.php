<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainee_uc3', function (Blueprint $table) {
            $table->json('peda_theo_timeline_overrides')->nullable()->after('peda_timeline_overrides');
        });
    }

    public function down(): void
    {
        Schema::table('trainee_uc3', function (Blueprint $table) {
            $table->dropColumn('peda_theo_timeline_overrides');
        });
    }
};
