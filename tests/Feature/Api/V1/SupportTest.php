<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use RefreshDatabase;

    private array $supportKeys = [
        'id',
        'userId',
        'message',
        'createdAt'
    ];

    public function test_store_support()
    {
        $this->authAdmin();

        $response = $this->postJson(route('supports.store'), [
            'userId' => Auth::id(),
            'message' => 'test'
        ])->assertCreated()
            ->assertJsonStructure($this->supportKeys)->json();

        $this->assertDatabaseHas('supports', [
            'id' => $response['id'],
            'message' => $response['message'],
        ]);
    }
}
