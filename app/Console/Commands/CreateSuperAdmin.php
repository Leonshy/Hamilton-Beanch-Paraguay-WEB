<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class CreateSuperAdmin extends Command
{
    protected $signature = 'hb:create-super-admin {email} {--name=Webmaster}';

    protected $description = 'Crea (o actualiza) un usuario admin protegido que no puede editarse ni eliminarse desde el panel';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name  = $this->option('name');

        $emailValidator = Validator::make(['email' => $email], ['email' => 'required|email:rfc,dns']);
        if ($emailValidator->fails()) {
            $this->error('Email inválido: ' . $emailValidator->errors()->first('email'));
            return self::FAILURE;
        }

        $password = $this->secret('Contraseña (mín. 10 caracteres, mayúscula, minúscula, número y símbolo)');
        $confirm  = $this->secret('Confirmar contraseña');

        if ($password !== $confirm) {
            $this->error('Las contraseñas no coinciden.');
            return self::FAILURE;
        }

        $passwordValidator = Validator::make(
            ['password' => $password],
            ['password' => ['required', Password::min(10)->mixedCase()->numbers()->symbols()]]
        );
        if ($passwordValidator->fails()) {
            $this->error($passwordValidator->errors()->first('password'));
            return self::FAILURE;
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'      => $name,
                'password'  => Hash::make($password),
                'is_active' => true,
            ]
        );

        // is_protected no es mass-assignable a propósito (ver App\Models\User) — se setea directo acá.
        $user->is_protected = true;
        $user->save();

        $user->syncRoles([$adminRole]);

        $this->info("✓ Usuario protegido listo: {$user->email} (rol: admin, is_protected: true)");
        return self::SUCCESS;
    }
}
