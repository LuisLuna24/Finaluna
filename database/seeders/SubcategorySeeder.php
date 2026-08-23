<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subcategories')->insert([
            ['id' => 1, 'category_id' => 1, 'nombre' => 'Renta', 'is_active' => 1, 'created_at' => '2026-08-22 20:42:09', 'updated_at' => '2026-08-22 20:42:09'],
            ['id' => 2, 'category_id' => 1, 'nombre' => 'Mantenimiento', 'is_active' => 1, 'created_at' => '2026-08-22 20:42:18', 'updated_at' => '2026-08-22 20:42:18'],
            ['id' => 3, 'category_id' => 1, 'nombre' => 'Pago de credito', 'is_active' => 1, 'created_at' => '2026-08-22 20:52:41', 'updated_at' => '2026-08-22 20:52:41'],
            ['id' => 4, 'category_id' => 1, 'nombre' => 'Pago de predial', 'is_active' => 1, 'created_at' => '2026-08-22 20:52:41', 'updated_at' => '2026-08-22 20:52:41'],
            ['id' => 5, 'category_id' => 2, 'nombre' => 'Despensa', 'is_active' => 1, 'created_at' => '2026-08-22 20:54:10', 'updated_at' => '2026-08-22 20:54:10'],
            ['id' => 6, 'category_id' => 2, 'nombre' => 'Fuera', 'is_active' => 1, 'created_at' => '2026-08-22 20:54:10', 'updated_at' => '2026-08-22 20:54:10'],
            ['id' => 7, 'category_id' => 3, 'nombre' => 'Mantenimiento', 'is_active' => 1, 'created_at' => '2026-08-22 20:59:54', 'updated_at' => '2026-08-22 20:59:54'],
            ['id' => 8, 'category_id' => 3, 'nombre' => 'Tenencia', 'is_active' => 1, 'created_at' => '2026-08-22 20:59:54', 'updated_at' => '2026-08-22 20:59:54'],
            ['id' => 9, 'category_id' => 3, 'nombre' => 'Reparación', 'is_active' => 1, 'created_at' => '2026-08-22 20:59:54', 'updated_at' => '2026-08-22 20:59:54'],
            ['id' => 10, 'category_id' => 3, 'nombre' => 'Verificación', 'is_active' => 1, 'created_at' => '2026-08-22 20:59:54', 'updated_at' => '2026-08-22 20:59:54'],
            ['id' => 11, 'category_id' => 4, 'nombre' => 'Vacaciones', 'is_active' => 1, 'created_at' => '2026-08-22 21:01:37', 'updated_at' => '2026-08-22 21:01:37'],
            ['id' => 12, 'category_id' => 4, 'nombre' => 'Salidas', 'is_active' => 1, 'created_at' => '2026-08-22 21:01:37', 'updated_at' => '2026-08-22 21:01:37'],
            ['id' => 13, 'category_id' => 5, 'nombre' => 'Colegiatura', 'is_active' => 1, 'created_at' => '2026-08-22 21:02:37', 'updated_at' => '2026-08-22 21:02:37'],
            ['id' => 14, 'category_id' => 5, 'nombre' => 'Útiles', 'is_active' => 1, 'created_at' => '2026-08-22 21:02:37', 'updated_at' => '2026-08-22 21:02:37'],
            ['id' => 15, 'category_id' => 5, 'nombre' => 'Cursos', 'is_active' => 1, 'created_at' => '2026-08-22 21:02:37', 'updated_at' => '2026-08-22 21:02:37'],
            ['id' => 16, 'category_id' => 5, 'nombre' => 'Documentos', 'is_active' => 1, 'created_at' => '2026-08-22 21:02:37', 'updated_at' => '2026-08-22 21:02:37'],
            ['id' => 17, 'category_id' => 5, 'nombre' => 'Uniformes', 'is_active' => 1, 'created_at' => '2026-08-22 21:02:37', 'updated_at' => '2026-08-22 21:02:37'],
            ['id' => 18, 'category_id' => 6, 'nombre' => 'Accidentes', 'is_active' => 1, 'created_at' => '2026-08-22 21:04:30', 'updated_at' => '2026-08-22 21:04:30'],
            ['id' => 19, 'category_id' => 6, 'nombre' => 'Legal', 'is_active' => 1, 'created_at' => '2026-08-22 21:04:30', 'updated_at' => '2026-08-22 21:04:30'],
            ['id' => 20, 'category_id' => 4, 'nombre' => 'Gasto hormigo', 'is_active' => 1, 'created_at' => '2026-08-22 21:05:23', 'updated_at' => '2026-08-22 21:05:23'],
            ['id' => 21, 'category_id' => 6, 'nombre' => 'Ropa', 'is_active' => 1, 'created_at' => '2026-08-22 21:05:50', 'updated_at' => '2026-08-22 21:05:50'],
            ['id' => 22, 'category_id' => 6, 'nombre' => 'Zapatos o tenis', 'is_active' => 1, 'created_at' => '2026-08-22 21:05:50', 'updated_at' => '2026-08-22 21:05:50'],
            ['id' => 23, 'category_id' => 7, 'nombre' => 'Inversión', 'is_active' => 1, 'created_at' => '2026-08-22 21:07:13', 'updated_at' => '2026-08-22 21:07:13'],
            ['id' => 24, 'category_id' => 7, 'nombre' => 'Seguro', 'is_active' => 1, 'created_at' => '2026-08-22 21:07:13', 'updated_at' => '2026-08-22 21:07:13'],
            ['id' => 25, 'category_id' => 7, 'nombre' => 'Plan de retiro', 'is_active' => 1, 'created_at' => '2026-08-22 21:07:13', 'updated_at' => '2026-08-22 21:07:13'],
            ['id' => 26, 'category_id' => 7, 'nombre' => 'Trading', 'is_active' => 1, 'created_at' => '2026-08-22 21:07:13', 'updated_at' => '2026-08-22 21:07:13'],
            ['id' => 27, 'category_id' => 7, 'nombre' => 'Crédito', 'is_active' => 1, 'created_at' => '2026-08-22 21:07:13', 'updated_at' => '2026-08-22 21:07:13'],
            ['id' => 28, 'category_id' => 8, 'nombre' => 'Medicinas', 'is_active' => 1, 'created_at' => '2026-08-22 21:08:00', 'updated_at' => '2026-08-22 21:08:00'],
            ['id' => 29, 'category_id' => 8, 'nombre' => 'Medico', 'is_active' => 1, 'created_at' => '2026-08-22 21:08:00', 'updated_at' => '2026-08-22 21:08:00'],
            ['id' => 30, 'category_id' => 8, 'nombre' => 'Veterinaria', 'is_active' => 1, 'created_at' => '2026-08-22 21:08:00', 'updated_at' => '2026-08-22 21:08:00'],
            ['id' => 31, 'category_id' => 9, 'nombre' => 'Agua', 'is_active' => 1, 'created_at' => '2026-08-22 21:09:36', 'updated_at' => '2026-08-22 21:09:36'],
            ['id' => 32, 'category_id' => 9, 'nombre' => 'Gas', 'is_active' => 1, 'created_at' => '2026-08-22 21:09:36', 'updated_at' => '2026-08-22 21:09:36'],
            ['id' => 33, 'category_id' => 9, 'nombre' => 'Internet', 'is_active' => 1, 'created_at' => '2026-08-22 21:09:36', 'updated_at' => '2026-08-22 21:09:36'],
            ['id' => 34, 'category_id' => 9, 'nombre' => 'Luz', 'is_active' => 1, 'created_at' => '2026-08-22 21:09:36', 'updated_at' => '2026-08-22 21:09:36'],
            ['id' => 35, 'category_id' => 9, 'nombre' => 'Suscripciones', 'is_active' => 1, 'created_at' => '2026-08-22 21:09:36', 'updated_at' => '2026-08-22 21:09:36'],
            ['id' => 36, 'category_id' => 9, 'nombre' => 'GYM', 'is_active' => 1, 'created_at' => '2026-08-22 21:09:36', 'updated_at' => '2026-08-22 21:09:36'],
            ['id' => 37, 'category_id' => 10, 'nombre' => 'Equipos', 'is_active' => 1, 'created_at' => '2026-08-22 21:10:33', 'updated_at' => '2026-08-22 21:10:33'],
            ['id' => 38, 'category_id' => 10, 'nombre' => 'Herramientas', 'is_active' => 1, 'created_at' => '2026-08-22 21:10:33', 'updated_at' => '2026-08-22 21:10:33'],
            ['id' => 39, 'category_id' => 10, 'nombre' => 'Licencias', 'is_active' => 1, 'created_at' => '2026-08-22 21:10:33', 'updated_at' => '2026-08-22 21:10:33'],
            ['id' => 40, 'category_id' => 10, 'nombre' => 'Mantenimiento', 'is_active' => 1, 'created_at' => '2026-08-22 21:10:33', 'updated_at' => '2026-08-22 21:10:33'],
            ['id' => 41, 'category_id' => 10, 'nombre' => 'Papelería', 'is_active' => 1, 'created_at' => '2026-08-22 21:10:33', 'updated_at' => '2026-08-22 21:10:33'],
            ['id' => 42, 'category_id' => 10, 'nombre' => 'Otro', 'is_active' => 1, 'created_at' => '2026-08-22 21:10:33', 'updated_at' => '2026-08-22 21:10:33'],
            ['id' => 43, 'category_id' => 11, 'nombre' => 'Pasaje', 'is_active' => 1, 'created_at' => '2026-08-22 21:11:07', 'updated_at' => '2026-08-22 21:11:07'],
            ['id' => 44, 'category_id' => 11, 'nombre' => 'Gasolina', 'is_active' => 1, 'created_at' => '2026-08-22 21:11:07', 'updated_at' => '2026-08-22 21:11:07'],
        ]);
    }
}
