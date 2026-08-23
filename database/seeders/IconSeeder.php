<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('icons')->insert([
            ['id' => 1, 'name' => 'Casa', 'icon' => 'home'],
            ['id' => 2, 'name' => 'Servicios', 'icon' => 'wrench-screwdriver'],
            ['id' => 3, 'name' => 'Educación', 'icon' => 'academic-cap'],
            ['id' => 4, 'name' => 'Salud', 'icon' => 'plus-circle'],
            ['id' => 5, 'name' => 'Financieros', 'icon' => 'credit-card'],
            ['id' => 6, 'name' => 'Trabajo', 'icon' => 'briefcase'],
            ['id' => 7, 'name' => 'Extraordinarios', 'icon' => 'rocket-launch'],
            ['id' => 8, 'name' => 'Diversión', 'icon' => 'sparkles'],
            ['id' => 9, 'name' => 'Auto', 'icon' => 'cog'],
            ['id' => 10, 'name' => 'Alimentos', 'icon' => 'building-storefront'],
            ['id' => 11, 'name' => 'Transporte', 'icon' => 'map-pin'],
        ]);
    }
}
