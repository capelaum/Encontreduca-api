<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMotiveTest extends TestCase
{
    use RefreshDatabase;

    private $motive;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();

        $this->motive = $this->createMotive();
    }

    public function test_admin_list_motives()
    {
        $response = $this->getJson(route('admin.motives.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'type',
                    'resourceComplaintsCount',
                    'reviewComplaintsCount'
                ]
            ])->json();
    }

    public function test_user_cannot_admin_list_motives()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.motives.index'))
            ->assertUnauthorized();
    }

    public function test_admin_show_motive()
    {
        $response = $this->getJson(route('admin.motives.show', $this->motive->id))
            ->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'type',
                'resourceComplaintsCount',
                'reviewComplaintsCount'
            ])->json();

//        dd($response);

        $this->assertEquals($this->motive->id, $response['id']);
    }

    public function test_user_cannot_admin_show_motive()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.motives.show', $this->motive->id))
            ->assertUnauthorized();
    }

    public function test_admin_create_motive()
    {
        $response = $this->postJson(route('admin.motives.store'), [
            'name' => 'Teste',
            'type' => 'resource_complaint'
        ])->assertCreated()
            ->assertJsonStructure([
                'id',
                'name',
                'type',
                'resourceComplaintsCount',
                'reviewComplaintsCount'
            ])->json();

        $this->assertEquals('Teste', $response['name']);
    }

    public function test_user_cannot_admin_create_motive()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->postJson(route('admin.motives.store'), [
            'name' => 'Teste',
            'type' => 'resource_complaint'
        ])->assertUnauthorized();
    }

    public function test_admin_update_motive()
    {
        $response = $this->putJson(route('admin.motives.update', $this->motive->id), [
            'name' => 'Teste',
            'type' => 'resource_complaint'
        ])->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
                'type',
                'resourceComplaintsCount',
                'reviewComplaintsCount'
            ])->json();

        $this->assertEquals('Teste', $response['name']);

        $this->assertDatabaseHas('motives', [
            'id' => $this->motive->id,
            'name' => 'Teste',
            'type' => 'resource_complaint'
        ]);
    }

    public function test_user_cannot_admin_update_motive()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->putJson(route('admin.motives.update', $this->motive->id), [
            'name' => 'Teste',
            'type' => 'resource_complaint'
        ])->assertUnauthorized();
    }

    public function test_admin_delete_motive()
    {
        $this->deleteJson(route('admin.motives.destroy', $this->motive->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('motives', [
            'id' => $this->motive->id
        ]);
    }

    public function test_user_cannot_admin_delete_motive()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->deleteJson(route('admin.motives.destroy', $this->motive->id))
            ->assertUnauthorized();
    }
}
