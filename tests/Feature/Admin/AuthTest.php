<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(string $password = 'Admin1234!'): User
    {
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'password'  => bcrypt($password),
            'is_active' => true,
        ]);
        $user->assignRole($role);

        return $user;
    }

    public function test_login_page_loads(): void
    {
        $this->get(route('admin.login'))->assertStatus(200);
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $user = $this->makeAdmin();

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'Admin1234!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = $this->makeAdmin();

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'contraseña-incorrecta',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_admin_cannot_login(): void
    {
        $user = $this->makeAdmin();
        $user->update(['is_active' => false]);

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'Admin1234!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_without_admin_role_cannot_login(): void
    {
        $user = User::factory()->create([
            'password'  => bcrypt('Admin1234!'),
            'is_active' => true,
        ]);

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'Admin1234!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_attempts(): void
    {
        $user = $this->makeAdmin();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.post'), [
                'email'    => $user->email,
                'password' => 'incorrecta',
            ]);
        }

        $response = $this->post(route('admin.login.post'), [
            'email'    => $user->email,
            'password' => 'incorrecta',
        ]);

        $response->assertStatus(429);
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }
}
