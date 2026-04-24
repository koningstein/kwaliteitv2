<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criterion_scores', function (Blueprint $table) {
            // MySQL staat niet toe dat je een unique index dropt die als FK dient.
            // Volgorde: drop FK op reporting_period_id en criterion_id eerst,
            // daarna de unique index, dan alles opnieuw aanmaken.
            $table->dropForeign(['criterion_id']);
            $table->dropForeign(['reporting_period_id']);
            $table->dropUnique('unique_score_per_period');

            // FK's herstellen
            $table->foreign('criterion_id')->references('id')->on('criteria')->onDelete('cascade');
            $table->foreign('reporting_period_id')->references('id')->on('reporting_periods')->onDelete('cascade');

            // Voeg team_id toe
            $table->foreignId('team_id')->nullable()->after('reporting_period_id')->constrained()->onDelete('cascade');

            // Nieuwe unique constraint: één score per criterium per periode per team
            $table->unique(['criterion_id', 'reporting_period_id', 'team_id'], 'unique_score_per_period_team');
        });
    }

    public function down(): void
    {
        Schema::table('criterion_scores', function (Blueprint $table) {
            $table->dropUnique('unique_score_per_period_team');
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
            $table->unique(['criterion_id', 'reporting_period_id'], 'unique_score_per_period');
        });
    }
};
