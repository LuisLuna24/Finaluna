<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expense_types')->insert([
            [
                'id' => 1,
                'nombre' => 'Fijos',
                'is_active' => 1,
                'created_at' => '2026-08-22 20:08:45',
                'updated_at' => '2026-08-22 20:08:45',
            ],
            [
                'id' => 2,
                'nombre' => 'Variables',
                'is_active' => 1,
                'created_at' => '2026-08-22 20:08:49',
                'updated_at' => '2026-08-22 20:08:49',
            ],
        ]);
    }
}
