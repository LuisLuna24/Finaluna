<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Definición escalable de roles y los módulos a los que tienen acceso.
     *
     * @return array<string, array<int, string>>
     */
    public static function roles(): array
    {
        return [
            'administrador' => [
                'budgets',
                'incomes',
                'expenses',
                'catalogs',
                'pockets',
                'users',
                'roles',
                'permissions',
            ],
            'usuario' => [
                'budgets',
                'incomes',
                'expenses',
                'pockets',
            ],
        ];
    }

    /**
     * Obtiene todos los permisos asociados a un rol, incluyendo las acciones
     * de cada módulo. Retorna una colección de nombres de permisos.
     *
     * @param  array<int, string>  $modules
     */
    public static function permissionsForModules(array $modules): array
    {
        $all = PermissionSeeder::permissions();

        return collect($modules)
            ->flatMap(fn(string $module) => $all[$module] ?? [])
            ->values()
            ->all();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (static::roles() as $roleName => $modules) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $permissions = static::permissionsForModules($modules);

            $role->syncPermissions($permissions);
        }
    }
}
