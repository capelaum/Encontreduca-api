<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_categories()
    {
        $response = $this->getJson(route('categories.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name'
                ]
            ])->json();

        $this->assertCount(7, $response);
    }
}
