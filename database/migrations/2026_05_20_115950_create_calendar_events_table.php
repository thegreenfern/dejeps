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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('asana_gid')->unique();
            $table->string('name');
            $table->string('section')->nullable();
            $table->string('event_type')->nullable();
            $table->date('start_on')->nullable();
            $table->date('due_on')->nullable();
            $table->boolean('completed')->default(false);
            $table->string('assignee_name')->nullable();
            $table->string('asana_url')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index(['section', 'start_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
