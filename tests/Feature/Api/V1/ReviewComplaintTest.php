<?php

namespace Tests\Feature\Api\V1;

use App\Models\Motive;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewComplaintTest extends TestCase
{
    use RefreshDatabase;

    private array $reviewComplaintKeys = [
        'id',
        'userId',
        'reviewId',
        'motiveId',
    ];

    public function test_store_review_complaint()
    {
        $this->authUser();

        $review = $this->createReview();
        $motive = $this->createMotive();

        $this->postJson(route('reviews.complaints.store', [
            'reviewId' => $review->id,
            'motiveId' => $motive->id,
        ]))
            ->assertCreated()
            ->assertJsonStructure($this->reviewComplaintKeys)
            ->json();

        $this->assertDatabaseHas('review_complaints', [
            'review_id' => $review->id,
            'motive_id' => $motive->id,
        ]);
    }
}
