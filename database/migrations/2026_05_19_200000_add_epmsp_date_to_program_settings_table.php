<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_settings', function (Blueprint $table) {
            $table->date('epmsp_date')->nullable()->after('uc2_jury_date');
        });
    }

    public function down(): void
    {
        Schema::table('program_settings', function (Blueprint $table) {
            $table->dropColumn('epmsp_date');
        });
    }
};
