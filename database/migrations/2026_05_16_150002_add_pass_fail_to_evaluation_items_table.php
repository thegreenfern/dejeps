<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluation_items', function (Blueprint $table) {
            // For uc4_mannequin: rating is NULL, passed + time_seconds are used instead
            $table->boolean('passed')->nullable()->after('rating');
            $table->unsignedSmallInteger('time_seconds')->nullable()->after('passed');

            // Make rating nullable so mannequin items can omit it
            $table->enum('rating', ['TI', 'I', 'S', 'M'])->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_items', function (Blueprint $table) {
            $table->dropColumn(['passed', 'time_seconds']);
            $table->enum('rating', ['TI', 'I', 'S', 'M'])->default('I')->change();
        });
    }
};
