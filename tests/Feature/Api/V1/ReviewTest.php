<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use  RefreshDatabase;

    public function test_list_reviews()
    {
        $this->createReviews(10);

        $response = $this->getJson(route('reviews.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'resourceId',
                    'author',
                    'authorAvatar',
                    'authorReviewCount',
                    'rating',
                    'comment',
                    'updatedAt'
                ]
            ])->json();
    }
}
