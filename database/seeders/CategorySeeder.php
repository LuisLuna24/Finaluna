<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['id' => 1, 'icon_id' => 1, 'nombre' => 'Hogar', 'is_active' => 1, 'created_at' => '2026-08-22 20:21:52', 'updated_at' => '2026-08-22 20:21:52', 'expense_type_id' => 1],
            ['id' => 2, 'icon_id' => 10, 'nombre' => 'Alimentos', 'is_active' => 1, 'created_at' => '2026-08-22 20:37:35', 'updated_at' => '2026-08-22 20:37:35', 'expense_type_id' => 1],
            ['id' => 3, 'icon_id' => 9, 'nombre' => 'Auto', 'is_active' => 1, 'created_at' => '2026-08-22 20:39:31', 'updated_at' => '2026-08-22 20:39:31', 'expense_type_id' => 2],
            ['id' => 4, 'icon_id' => 8, 'nombre' => 'Diversión', 'is_active' => 1, 'created_at' => '2026-08-22 20:39:51', 'updated_at' => '2026-08-22 20:39:51', 'expense_type_id' => 2],
            ['id' => 5, 'icon_id' => 3, 'nombre' => 'Educación', 'is_active' => 1, 'created_at' => '2026-08-22 20:40:04', 'updated_at' => '2026-08-22 20:40:04', 'expense_type_id' => 1],
            ['id' => 6, 'icon_id' => 7, 'nombre' => 'Extraordinarios', 'is_active' => 1, 'created_at' => '2026-08-22 20:40:18', 'updated_at' => '2026-08-22 20:40:18', 'expense_type_id' => 2],
            ['id' => 7, 'icon_id' => 5, 'nombre' => 'Financieros', 'is_active' => 1, 'created_at' => '2026-08-22 20:40:30', 'updated_at' => '2026-08-22 20:40:30', 'expense_type_id' => 1],
            ['id' => 8, 'icon_id' => 4, 'nombre' => 'Salud', 'is_active' => 1, 'created_at' => '2026-08-22 20:40:55', 'updated_at' => '2026-08-22 20:40:55', 'expense_type_id' => 2],
            ['id' => 9, 'icon_id' => 2, 'nombre' => 'Servicios', 'is_active' => 1, 'created_at' => '2026-08-22 20:41:08', 'updated_at' => '2026-08-22 20:41:08', 'expense_type_id' => 1],
            ['id' => 10, 'icon_id' => 6, 'nombre' => 'Trabajo', 'is_active' => 1, 'created_at' => '2026-08-22 20:41:20', 'updated_at' => '2026-08-22 20:41:20', 'expense_type_id' => 2],
            ['id' => 11, 'icon_id' => 11, 'nombre' => 'Transporte', 'is_active' => 1, 'created_at' => '2026-08-22 20:41:44', 'updated_at' => '2026-08-22 20:41:44', 'expense_type_id' => 2],
        ]);
    }
}
