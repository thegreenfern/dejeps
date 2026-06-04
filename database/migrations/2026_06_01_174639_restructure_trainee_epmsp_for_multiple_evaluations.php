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
        Schema::table('trainee_epmsp', function (Blueprint $table) {
            $table->dropUnique('trainee_epmsp_trainee_id_type_unique');
            $table->dropColumn('certification');
            $table->date('evaluated_at')->nullable()->after('type');
            $table->decimal('note_globale', 3, 2)->nullable()->after('ratings');
        });
    }

    public function down(): void
    {
        Schema::table('trainee_epmsp', function (Blueprint $table) {
            $table->dropColumn(['evaluated_at', 'note_globale']);
            $table->enum('certification', ['favorable', 'defavorable'])->nullable()->after('status');
            $table->unique(['trainee_id', 'type']);
        });
    }
};
