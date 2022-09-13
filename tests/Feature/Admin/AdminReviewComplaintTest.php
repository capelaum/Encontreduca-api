<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminReviewComplaintTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();
    }

    private array $reviewComplaintKeys = [
        'id',
        'userId',
        'author',
        'authorEmail',
        'authorAvatar',
        'reviewId',
        'review',
        'motiveId',
        'motiveName',
        'createdAt',
    ];

    public function test_admin_list_reviews_complaints()
    {
        $this->createReviewComplaint();

        $this->getJson(route('admin.reviews.complaints.index', [
            'search' => 'name',
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->reviewComplaintKeys
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'total'
                ]
            ]);
    }

    public function test_user_cannot_admin_list_reviews_complaints()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->createReviewComplaint();

        $this->getJson(route('admin.reviews.complaints.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_show_review_complaint()
    {
        $this->authAdmin();

        $reviewComplaint = $this->createReviewComplaint();

        $this->getJson(route('admin.reviews.complaints.show', $reviewComplaint->id))
            ->assertOk()
            ->assertJsonStructure($this->reviewComplaintKeys)
            ->json();
    }

    public function test_user_cannot_admin_show_review_complaint()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $reviewComplaint = $this->createReviewComplaint();

        $this->getJson(route('admin.reviews.complaints.show', $reviewComplaint->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_delete_review_complaint()
    {
        $reviewComplaint = $this->createReviewComplaint();

        $this->deleteJson(route('admin.reviews.complaints.destroy', $reviewComplaint->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('review_complaints', [
            'id' => $reviewComplaint->id
        ]);
    }

    public function test_user_cannot_admin_delete_review_complaint()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $reviewComplaint = $this->createReviewComplaint();

        $this->deleteJson(route('admin.reviews.complaints.destroy', $reviewComplaint->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
