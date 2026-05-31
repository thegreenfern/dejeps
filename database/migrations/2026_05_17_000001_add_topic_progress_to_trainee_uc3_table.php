<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainee_uc3', function (Blueprint $table) {
            $table->json('topic_progress')->nullable()->after('ratings');
        });
    }

    public function down(): void
    {
        Schema::table('trainee_uc3', function (Blueprint $table) {
            $table->dropColumn('topic_progress');
        });
    }
};
