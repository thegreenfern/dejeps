<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competencies', function (Blueprint $table) {
            // positioning = initial self-assessment (1–3 scale)
            // certification = official CREPS evaluation grids (TI/I/S/M)
            $table->enum('framework', ['positioning', 'certification'])
                  ->default('positioning')
                  ->after('uc');
        });
    }

    public function down(): void
    {
        Schema::table('competencies', function (Blueprint $table) {
            $table->dropColumn('framework');
        });
    }
};
