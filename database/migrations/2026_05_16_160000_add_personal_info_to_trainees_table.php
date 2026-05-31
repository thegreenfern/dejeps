<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->string('email', 200)->nullable()->after('name');
            $table->string('phone', 50)->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('photo_path')->nullable()->after('date_of_birth');
            $table->string('cv_path')->nullable()->after('photo_path');
        });

        Schema::table('trainee_profiles', function (Blueprint $table) {
            $table->text('trainee_comments')->nullable()->after('big5_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('trainees', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'date_of_birth', 'photo_path', 'cv_path']);
        });
        Schema::table('trainee_profiles', function (Blueprint $table) {
            $table->dropColumn('trainee_comments');
        });
    }
};
