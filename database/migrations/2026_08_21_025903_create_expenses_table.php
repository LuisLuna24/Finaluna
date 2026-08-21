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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('budget_id')
                ->constrained('budgets')
                ->cascadeOnDelete();

            $table->foreignId('subcategory_id')
                ->constrained('subcategories')
                ->restrictOnDelete();

            $table->foreignId('expense_type_id')
                ->constrained('expense_types')
                ->restrictOnDelete();

            $table->foreignId('payment_method_id')
                ->constrained('payment_methods')
                ->restrictOnDelete();

            $table->date('fecha');

            $table->string('descripcion', 255);

            $table->decimal('total', 10, 2);

            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
