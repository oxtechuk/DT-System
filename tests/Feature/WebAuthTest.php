<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_default_route_shows_login_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Workspace Hub')
            ->assertSee('تسجيل الدخول');
    }

    public function test_user_can_login_via_web_form(): void
    {
        $user = User::factory()->create([
            'email'    => 'admin@workspace.local',
            'password' => bcrypt('password'),
            'status'   => 'active',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@workspace.local',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard_and_logout(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $user->assignRole('owner');

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200)
            ->assertSee($user->name);

        $logout = $this->actingAs($user)->post('/logout');
        $logout->assertRedirect('/login');
        $this->assertGuest();
    }
}
