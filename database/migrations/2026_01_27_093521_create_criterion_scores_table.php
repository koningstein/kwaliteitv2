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
        Schema::create('criterion_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')->constrained()->onDelete('cascade');
            $table->foreignId('reporting_period_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['sufficient', 'attention', 'insufficient'])->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->unique(['criterion_id', 'reporting_period_id'], 'unique_score_per_period');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterion_scores');
    }
};
