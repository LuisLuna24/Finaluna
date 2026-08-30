<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('seeds permissions and roles with the correct module structure', function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);

    expect(Permission::count())->toBe(collect(PermissionSeeder::permissions())->flatten()->count())
        ->and(Role::count())->toBe(count(RoleSeeder::roles()));

    $admin = Role::findByName('administrador');
    $user = Role::findByName('usuario');

    $adminPermissionNames = $admin->permissions->pluck('name');

    expect($adminPermissionNames)->toContain('view budgets', 'create budgets', 'delete budgets')
        ->and($adminPermissionNames)->toContain('view users', 'view roles', 'view permissions')
        ->and($user->permissions)->toHaveCount(20);
});

it('assigns the administrator role to the user seeded by UserSeeder', function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
    $this->seed(UserSeeder::class);

    $user = User::where('email', 'eduarlun4@gmail.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->hasRole('administrador'))->toBeTrue()
        ->and($user->hasPermissionTo('view budgets'))->toBeTrue()
        ->and($user->hasPermissionTo('view users'))->toBeTrue();
});

it('is idempotent: seeding roles and permissions twice keeps a single copy', function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);

    expect(Permission::where('name', 'view budgets')->count())->toBe(1)
        ->and(Role::where('name', 'administrador')->count())->toBe(1);
});
