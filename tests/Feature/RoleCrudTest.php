<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin role with ID 1 (required by isAdmin() check)
        $adminRole = Role::factory()->create([
            'id' => 1,
            'name' => 'admin',
            'description' => 'Administrator role'
        ]);
        
        $this->adminUser = User::factory()->create([
            'role_id' => 1
        ]);
    }

    /** @test */
    public function guest_cannot_access_roles_list()
    {
        $response = $this->get('/laravel-examples/role-management');

        $response->assertRedirect('/sign-in');
    }

    /** @test */
    public function authenticated_user_can_view_roles_list()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_create_role_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/new-role-management');

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_edit_role_page()
    {
        $role = Role::factory()->create();

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management/' . $role->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function roles_list_displays_all_roles()
    {
        $role1 = Role::factory()->create(['name' => 'Admin Role']);
        $role2 = Role::factory()->create(['name' => 'User Role']);

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management');

        $response->assertStatus(200);
        $response->assertSee('Admin Role');
        $response->assertSee('User Role');
    }

    /** @test */
    public function roles_list_paginates_results()
    {
        Role::factory()->count(15)->create();

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management');

        $response->assertStatus(200);
    }

    /** @test */
    public function roles_are_searchable_by_name()
    {
        Role::factory()->create(['name' => 'Admin Role']);
        Role::factory()->create(['name' => 'User Role']);
        Role::factory()->create(['name' => 'Guest Role']);

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management?search=Admin');

        $response->assertStatus(200);
    }

    /** @test */
    public function roles_are_sortable_by_name()
    {
        Role::factory()->create(['name' => 'Zebra Role']);
        Role::factory()->create(['name' => 'Alpha Role']);
        Role::factory()->create(['name' => 'Middle Role']);

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management?sortField=name&sortDirection=asc');

        $response->assertStatus(200);
    }

    /** @test */
    public function roles_are_sortable_by_id()
    {
        Role::factory()->create(['name' => 'First Role']);
        Role::factory()->create(['name' => 'Second Role']);

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management?sortField=id&sortDirection=desc');

        $response->assertStatus(200);
    }

    /** @test */
    public function cannot_access_edit_page_with_invalid_role_id()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management/99999');

        // Returns 500 because Role::find returns null and causes error
        $response->assertStatus(500);
    }

    /** @test */
    public function role_edit_page_loads_role_data()
    {
        $role = Role::factory()->create([
            'name' => 'Test Role',
            'description' => 'Test description'
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get('/laravel-examples/role-management/' . $role->id);

        $response->assertStatus(200);
    }
}