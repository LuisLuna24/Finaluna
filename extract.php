<?php

use Illuminate\Support\Facades\Schema;

$models = [
    'Budget',
    'BudgetPocketItem',
    'Category',
    'Expense',
    'ExpenseType',
    'Icon',
    'Income',
    'PaymentMehod', // notice typo in model name
    'Pocket',
    'PocketItem',
    'Subcategory',
    'User',
];

// we want to list fillables (all columns except id, created_at, updated_at)
// and belongsTo relationships based on *_id
$output = [];
foreach ($models as $modelName) {
    $className = '\\App\\Models\\'.$modelName;
    if (! class_exists($className)) {
        continue;
    }
    $model = new $className;
    $table = $model->getTable();
    $columns = Schema::getColumnListing($table);
    $fillable = array_values(array_filter($columns, function ($c) {
        return ! in_array($c, ['id', 'created_at', 'updated_at']);
    }));
    $output[$modelName] = [
        'fillable' => $fillable,
    ];
}

echo json_encode($output, JSON_PRETTY_PRINT);
