<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('action_points', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['team_id']);
            // Re-add as nullable with the same cascade behaviour
            $table->foreignId('team_id')->nullable()->change();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('action_points', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->foreignId('team_id')->nullable(false)->change();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }
};
