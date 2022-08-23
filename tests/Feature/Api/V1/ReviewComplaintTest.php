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

    public function test_list_reviews_complaints()
    {
        $this->authAdmin();

        $this->createReviewComplaint();

        $this->getJson(route('reviews.complaints.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->reviewComplaintKeys])->json();
    }

    public function test_user_cannot_list_reviews_complaints()
    {
        $this->authUser();

        $this->createReviewComplaint();

        $this->withExceptionHandling();

        $this->getJson(route('reviews.complaints.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_review_complaint()
    {
        $this->authAdmin();

        $reviewComplaint = $this->createReviewComplaint();

        $this->getJson(route('reviews.complaints.show', $reviewComplaint->id))
            ->assertOk()
            ->assertJsonStructure($this->reviewComplaintKeys)->json();
    }

    public function test_user_cannot_show_review_complaint()
    {
        $this->authUser();

        $reviewComplaint = $this->createReviewComplaint();

        $this->withExceptionHandling();

        $this->getJson(route('reviews.complaints.show', $reviewComplaint->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

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
            ->assertJsonStructure($this->reviewComplaintKeys)->json();

        $this->assertDatabaseHas('review_complaints', [
            'review_id' => $review->id,
            'motive_id' => $motive->id,
        ]);
    }
}
