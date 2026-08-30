<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Definición escalable de permisos agrupados por módulo.
     *
     * @return array<string, array<string>>
     */
    public static function permissions(): array
    {
        return [
            'budgets' => [
                'view budgets',
                'create budgets',
                'edit budgets',
                'delete budgets',
            ],
            'incomes' => [
                'view incomes',
                'create incomes',
                'edit incomes',
                'delete incomes',
            ],
            'expenses' => [
                'view expenses',
                'create expenses',
                'edit expenses',
                'delete expenses',
            ],
            'catalogs' => [
                'view catalogs',
                'create catalogs',
                'edit catalogs',
                'delete catalogs',
            ],
            'pockets' => [
                'view pockets',
                'create pockets',
                'edit pockets',
                'delete pockets',
            ],
            'users' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
            ],
            'roles' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
            'permissions' => [
                'view permissions',
                'create permissions',
                'edit permissions',
                'delete permissions',
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (static::permissions() as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
