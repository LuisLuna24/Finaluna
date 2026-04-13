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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->decimal('planned_amount', 10, 2);
            $table->decimal('savings_amount', 10, 2)->default(0);
            $table->tinyInteger('month');
            $table->smallInteger('year');
            $table->timestamps();

            $table->unique(['household_id', 'category_id', 'month', 'year'], 'budget_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
