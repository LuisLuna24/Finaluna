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
        Schema::create('budget_pocket_items', function (Blueprint $table) {
            $table->foreignId('budget_id')
                ->constrained('budgets')
                ->cascadeOnDelete();

            $table->foreignId('pocket_item_id')
                ->constrained('pocket_items')
                ->cascadeOnDelete();

            $table->primary([
                'budget_id',
                'pocket_item_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_pocket_items');
    }
};
