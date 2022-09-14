<?php

namespace Tests\Feature\Admin;

use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminReviewTest extends TestCase
{
    use  RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();
    }

    private array $reviewResourceKeys = [
        'id',
        'userId',
        'resourceId',
        'author',
        'authorAvatar',
        'authorEmail',
        'rating',
        'comment',
        'updatedAt'
    ];

    public function test_admin_list_reviews()
    {
        $this->createReview();

        $this->getJson(route('admin.reviews.index', [
            'search' => 'test'
        ]))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->reviewResourceKeys
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

    public function test_user_cannot_admin_list_reviews()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $this->createReview();
        $this->getJson(route('admin.reviews.index'))
            ->assertUnauthorized();
    }

    public function test_admin_show_review()
    {
        $review = $this->createReview();

        $response = $this->getJson(route('admin.reviews.show', $review->id))
            ->assertOk()
            ->json();

        $reviewResponse = (new ReviewResource($review))->toArray($review);

        $this->assertEquals($response, $reviewResponse);
    }

    public function test_admin_store_review()
    {
        $this->authAdmin();
        $resource = $this->createResource();
        $review = Review::factory()->make();

        $data = [
            'resourceId' => $resource->id,
            'rating' => $review->rating,
            'comment' => $review->comment
        ];

        $this->postJson(route('admin.reviews.store'), $data)
            ->assertCreated()
            ->assertJsonStructure($this->reviewResourceKeys);
    }

    public function test_user_cannot_admin_show_review()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $review = $this->createReview();

        $this->getJson(route('admin.reviews.show', $review->id))
            ->assertUnauthorized();
    }

    public function test_admin_cannot_create_two_reviews_on_same_resource()
    {
        $this->authAdmin();

        $resource = $this->createResource();

        $review = $this->createReview([
            'user_id' => Auth::user()->id,
            'resource_id' => $resource->id
        ]);

        $this->postJson(route('admin.reviews.store'), [
            'resourceId' => $resource->id,
            'rating' => $review->rating,
            'comment' => $review->comment
        ])->assertStatus(409)
            ->assertJson([
                'message' => 'Você já avaliou este recurso.'
            ]);
    }

    public function test_admin_update_review()
    {
        $this->authAdmin();
        $review = $this->createReview(['user_id' => Auth::id()]);

        $this->putJson(route('admin.reviews.update', $review->id), [
            'rating' => 5,
            'comment' => 'New comment'
        ])->assertOk()
            ->assertJsonStructure($this->reviewResourceKeys);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 5,
            'comment' => 'New comment'
        ]);
    }

    public function test_user_cannot_admin_update_review()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $review = $this->createReview(['user_id' => Auth::id()]);

        $this->putJson(route('admin.reviews.update', $review->id), [
            'rating' => 5,
            'comment' => 'New comment'
        ])->assertUnauthorized();
    }

    public function test_admin_delete_review()
    {
        $this->authAdmin();

        $review = $this->createReview(['user_id' => Auth::id()]);

        $this->deleteJson(route('admin.reviews.destroy', $review->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id
        ]);
    }

    public function test_user_cannot_admin_delete_review()
    {
        $this->authUser();

        $this->withExceptionHandling();

        $review = $this->createReview(['user_id' => Auth::id()]);

        $this->deleteJson(route('admin.reviews.destroy', $review->id))
            ->assertUnauthorized();
    }
}
