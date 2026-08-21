<?php

$modelsData = [
    'Budget' => [
        'fillable' => ["user_id", "nombre", "fecha_inicio", "fecha_fin", "presupuesto", "is_active"],
        'relationships' => [
            'user' => 'User',
            'expenses' => ['hasMany', 'Expense'],
            'incomes' => ['hasMany', 'Income'],
            'pocketItems' => ['belongsToMany', 'PocketItem', 'budget_pocket_items']
        ]
    ],
    'BudgetPocketItem' => [
        'fillable' => ["budget_id", "pocket_item_id"],
        'relationships' => [
            'budget' => 'Budget',
            'pocketItem' => 'PocketItem'
        ]
    ],
    'Category' => [
        'fillable' => ["icon_id", "nombre", "is_active"],
        'relationships' => [
            'icon' => 'Icon',
            'subcategories' => ['hasMany', 'Subcategory']
        ]
    ],
    'Expense' => [
        'fillable' => ["user_id", "budget_id", "subcategory_id", "expense_type_id", "payment_method_id", "fecha", "descripcion", "total", "notes", "is_active"],
        'relationships' => [
            'user' => 'User',
            'budget' => 'Budget',
            'subcategory' => 'Subcategory',
            'expenseType' => 'ExpenseType',
            'paymentMethod' => 'PaymentMehod'
        ]
    ],
    'ExpenseType' => [
        'fillable' => ["nombre", "is_active"],
        'relationships' => [
            'expenses' => ['hasMany', 'Expense']
        ]
    ],
    'Icon' => [
        'fillable' => ["name"],
        'relationships' => [
            'categories' => ['hasMany', 'Category'],
            'pockets' => ['hasMany', 'Pocket']
        ]
    ],
    'Income' => [
        'fillable' => ["user_id", "budget_id", "payment_method_id", "fecha", "descripcion", "total", "porcentaje_ahorro", "notes", "is_active"],
        'relationships' => [
            'user' => 'User',
            'budget' => 'Budget',
            'paymentMethod' => 'PaymentMehod'
        ]
    ],
    'PaymentMehod' => [
        'fillable' => ["nombre", "is_active"],
        'relationships' => [
            'expenses' => ['hasMany', 'Expense'],
            'incomes' => ['hasMany', 'Income'],
            'pocketItems' => ['hasMany', 'PocketItem']
        ],
        'table' => 'payment_methods'
    ],
    'Pocket' => [
        'fillable' => ["user_id", "icon_id", "fecha_inicio", "fecha_fin", "meta_apartado", "is_active"],
        'relationships' => [
            'user' => 'User',
            'icon' => 'Icon',
            'pocketItems' => ['hasMany', 'PocketItem']
        ]
    ],
    'PocketItem' => [
        'fillable' => ["pocket_id", "payment_method_id", "descripcion", "fecha", "monto"],
        'relationships' => [
            'pocket' => 'Pocket',
            'paymentMethod' => 'PaymentMehod',
            'budgets' => ['belongsToMany', 'Budget', 'budget_pocket_items']
        ]
    ],
    'Subcategory' => [
        'fillable' => ["category_id", "nombre", "is_active"],
        'relationships' => [
            'category' => 'Category',
            'expenses' => ['hasMany', 'Expense']
        ]
    ]
];

foreach ($modelsData as $modelName => $data) {
    $filePath = __DIR__ . '/app/Models/' . $modelName . '.php';
    if (!file_exists($filePath)) continue;

    $content = file_get_contents($filePath);
    
    // Check if fillable already exists
    if (strpos($content, '$fillable') !== false) {
        continue; // skip
    }

    $fillableArray = "[\n        '" . implode("',\n        '", $data['fillable']) . "'\n    ]";
    
    $properties = "\n    protected \$fillable = $fillableArray;\n";
    if (isset($data['table'])) {
        $properties .= "\n    protected \$table = '{$data['table']}';\n";
    }

    $methods = "";
    foreach ($data['relationships'] as $methodName => $relInfo) {
        if (is_array($relInfo)) {
            $type = $relInfo[0]; // hasMany, belongsToMany
            $relatedModel = $relInfo[1];
            if ($type === 'belongsToMany') {
                $table = $relInfo[2];
                $methods .= "\n    public function {$methodName}()\n    {\n        return \$this->belongsToMany({$relatedModel}::class, '{$table}');\n    }\n";
            } else {
                $methods .= "\n    public function {$methodName}()\n    {\n        return \$this->{$type}({$relatedModel}::class);\n    }\n";
            }
        } else {
            // belongsTo
            $methods .= "\n    public function {$methodName}()\n    {\n        return \$this->belongsTo({$relInfo}::class);\n    }\n";
        }
    }

    // Insert before last }
    $pos = strrpos($content, '}');
    if ($pos !== false) {
        $newContent = substr($content, 0, $pos) . $properties . $methods . substr($content, $pos);
        file_put_contents($filePath, $newContent);
    }
}

echo "Models updated successfully.";
