<?php

namespace App\Livewire\Forms\Budgets;

use App\Models\Category;
use App\Models\ExpenseType;
use App\Models\Subcategory;
use Illuminate\Support\Collection;
use Livewire\Form;

class BudgetItemForm extends Form
{
    public bool $modal = false;

    public ?int $editingId = null;

    public ?int $budgetCategoryId = null;

    public ?int $budgetSubcategoryId = null;

    public ?int $budgetExpenseTypeId = null;

    public ?string $budgetAmount = null;

    public ?string $budgetNotes = null;

    public function updatedBudgetExpenseTypeId($value)
    {
        $this->budgetCategoryId = null;
        $this->budgetSubcategoryId = null;
    }

    public function updatedBudgetCategoryId($value)
    {
        $category = Category::find($value);
        if ($category && ! $this->budgetExpenseTypeId) {
            $this->budgetExpenseTypeId = $category->expense_type_id;
        }
        $this->budgetSubcategoryId = null;
    }

    public function getExpenseTypes(): Collection
    {
        return ExpenseType::where('is_active', true)->get();
    }

    public function getCategories(): Collection
    {
        $query = Category::where('is_active', true);
        if ($this->budgetExpenseTypeId) {
            $query->where('expense_type_id', $this->budgetExpenseTypeId);
        }

        return $query->get();
    }

    public function getSubcategories(): Collection
    {
        if ($this->budgetCategoryId) {
            return Subcategory::where('category_id', $this->budgetCategoryId)->where('is_active', true)->get();
        }

        return collect();
    }
}
