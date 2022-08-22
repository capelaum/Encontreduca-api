<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MotiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_motives()
    {
        $response = $this->getJson(route('motives.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'type'
                ]
            ])->json();

        $this->assertCount(13, $response);
    }
}
