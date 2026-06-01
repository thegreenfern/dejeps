<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_settings', function (Blueprint $table) {
            $table->unsignedSmallInteger('threshold_obs_sd')->default(2)->after('epmsp_date');
            $table->unsignedSmallInteger('threshold_sd_si')->default(2)->after('threshold_obs_sd');
            $table->unsignedSmallInteger('threshold_si_auto')->default(2)->after('threshold_sd_si');
        });

        Schema::table('trainee_profiles', function (Blueprint $table) {
            $table->unsignedSmallInteger('peda_threshold_obs_sd')->nullable()->after('trainee_comments');
            $table->unsignedSmallInteger('peda_threshold_sd_si')->nullable()->after('peda_threshold_obs_sd');
            $table->unsignedSmallInteger('peda_threshold_si_auto')->nullable()->after('peda_threshold_sd_si');
        });
    }

    public function down(): void
    {
        Schema::table('program_settings', function (Blueprint $table) {
            $table->dropColumn(['threshold_obs_sd', 'threshold_sd_si', 'threshold_si_auto']);
        });

        Schema::table('trainee_profiles', function (Blueprint $table) {
            $table->dropColumn(['peda_threshold_obs_sd', 'peda_threshold_sd_si', 'peda_threshold_si_auto']);
        });
    }
};
