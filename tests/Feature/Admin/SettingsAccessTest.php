<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingsAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole($role);

        return $user;
    }

    public function test_editor_cannot_access_sensitive_settings(): void
    {
        $editor = $this->makeUser('editor');

        $this->actingAs($editor)->get(route('admin.settings.general'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.settings.integrations'))->assertForbidden();
        $this->actingAs($editor)->post(route('admin.settings.maintenance'), ['value' => '1'])->assertForbidden();
    }

    public function test_editor_cannot_inject_scripts_via_integrations(): void
    {
        $editor = $this->makeUser('editor');

        $this->actingAs($editor)
            ->post(route('admin.settings.integrations.save'), [
                'custom_scripts_head' => '<script>alert(1)</script>',
            ])
            ->assertForbidden();

        $this->assertNull(SiteSetting::where('key', 'custom_scripts_head')->value('value'));
    }

    public function test_editor_can_still_access_contact_social_and_home_settings(): void
    {
        $editor = $this->makeUser('editor');

        $this->actingAs($editor)->get(route('admin.settings.contact'))->assertOk();
        $this->actingAs($editor)->get(route('admin.settings.social'))->assertOk();
        $this->actingAs($editor)->get(route('admin.settings.home'))->assertOk();
    }

    public function test_admin_can_access_sensitive_settings(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->get(route('admin.settings.general'))->assertOk();
        $this->actingAs($admin)->get(route('admin.settings.integrations'))->assertOk();
    }
}
