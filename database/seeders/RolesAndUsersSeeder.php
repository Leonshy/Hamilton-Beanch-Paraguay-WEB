<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin  = Role::firstOrCreate(['name' => 'admin',  'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

        $user = User::firstOrCreate(
            ['email' => 'admin@hamiltonbeach.com.py'],
            [
                'name'      => 'Administrador',
                'password'  => Hash::make('Admin1234!'),
                'is_active' => true,
            ]
        );
        $user->syncRoles([$admin]);
    }
}
