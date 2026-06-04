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
        Schema::table('direction_plongee_evaluations', function (Blueprint $table) {
            $table->dropColumn([
                'comp_orientation', 'comp_gestion_group', 'comp_chaine_secours',
                'comp_remontee_assist', 'comp_gestion_env', 'comp_materiel',
            ]);
            $table->unsignedTinyInteger('comp_palanquees')->nullable()->after('status');
            $table->unsignedTinyInteger('comp_consignes')->nullable()->after('comp_palanquees');
            $table->unsignedTinyInteger('comp_reglementation')->nullable()->after('comp_consignes');
            $table->unsignedTinyInteger('comp_site')->nullable()->after('comp_reglementation');
            $table->unsignedTinyInteger('comp_navigation')->nullable()->after('comp_site');
            $table->unsignedTinyInteger('comp_secours')->nullable()->after('comp_navigation');
        });
    }

    public function down(): void
    {
        Schema::table('direction_plongee_evaluations', function (Blueprint $table) {
            $table->dropColumn([
                'comp_palanquees', 'comp_consignes', 'comp_reglementation',
                'comp_site', 'comp_navigation', 'comp_secours',
            ]);
            $table->unsignedTinyInteger('comp_orientation')->nullable()->after('status');
            $table->unsignedTinyInteger('comp_gestion_group')->nullable()->after('comp_orientation');
            $table->unsignedTinyInteger('comp_chaine_secours')->nullable()->after('comp_gestion_group');
            $table->unsignedTinyInteger('comp_remontee_assist')->nullable()->after('comp_chaine_secours');
            $table->unsignedTinyInteger('comp_gestion_env')->nullable()->after('comp_remontee_assist');
            $table->unsignedTinyInteger('comp_materiel')->nullable()->after('comp_gestion_env');
        });
    }
};
