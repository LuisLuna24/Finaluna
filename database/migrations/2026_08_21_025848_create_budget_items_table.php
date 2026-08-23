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
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('budget_id')
                ->constrained('budgets')
                ->cascadeOnDelete();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();
            $table->foreignId('subcategory_id')
                ->constrained('subcategories')
                ->restrictOnDelete();
            $table->foreignId('expense_type_id')
                ->constrained('expense_types')
                ->restrictOnDelete();
            $table->decimal('presupuesto', 10, 2)->default(0);
            $table->decimal('gasto_real', 10, 2)->default(0);
            $table->text('notas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
