<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();
    }

    public function test_admin_list_categories()
    {
        $response = $this->getJson(route('admin.categories.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'resourcesCount'
                ]
            ])->json();

        $this->assertCount(7, $response);
    }

    public function test_admin_show_category()
    {
        $category = Category::factory()->create();

        $this->getJson(route('admin.categories.show', $category->id))
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'resourcesCount'
            ]);
    }

    public function test_admin_create_category()
    {
        $response = $this->postJson(route('admin.categories.store'), [
            'name' => 'Category Test'
        ])->assertCreated()->json();

        $this->assertDatabaseHas('categories', [
            'id' => $response['id'],
            'name' => 'Category Test'
        ]);
    }

    public function test_user_cannot_admin_create_category()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->postJson(route('admin.categories.store'), [
            'name' => 'Category Test'
        ])->assertUnauthorized();
    }

    public function test_admin_update_category()
    {
        $category = $this->createCategory();

        $response = $this->putJson(route('admin.categories.update', $category->id), [
            'name' => 'Category Test'
        ])->assertOk()->json();

        $this->assertDatabaseHas('categories', [
            'id' => $response['id'],
            'name' => 'Category Test'
        ]);
    }

    public function test_user_cannot_admin_update_category()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $category = $this->createCategory();

        $response = $this->putJson(route('admin.categories.update', $category->id), [
            'name' => 'Category Test'
        ])->assertUnauthorized();
    }

    public function test_admin_delete_category()
    {
        $category = $this->createCategory();

        $this->deleteJson(route('admin.categories.destroy', $category->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_user_cannot_admin_delete_category()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $category = $this->createCategory();

        $this->deleteJson(route('admin.categories.destroy', $category->id))
            ->assertUnauthorized();
    }
}
