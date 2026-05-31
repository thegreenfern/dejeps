<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // Maps directly to the 8 official CREPS evaluation grids
            $table->enum('exam_type', [
                'uc1_projet',
                'uc2_projet',
                'uc3_ecrite',
                'uc3_peda_theorique',
                'uc3_peda_pratique',
                'uc4_peda_pratique',
                'uc4_direction',
                'uc4_mannequin',
            ])->nullable()->after('eval_type');
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn('exam_type');
        });
    }
};
