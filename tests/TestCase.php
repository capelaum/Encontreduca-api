<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\{ReviewSeeder, CategorySeeder, MotiveSeeder};
use App\Models\{
    User,
    Motive,
    Resource,
    ResourceComplaint,
    ResourceVote,
    Review,
    ReviewComplaint,
    Support
};

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup(): void
    {
        parent::setup();

        $this->withoutExceptionHandling();

        $this->seed(CategorySeeder::class);
        $this->seed(MotiveSeeder::class);

        User::factory(10)->create();
        Resource::factory(10)->create();

        $this->seed(ReviewSeeder::class);
    }

    public function createUser(array $args = [])
    {
        return User::factory()->create($args);
    }

    public function authUser()
    {
        $user = $this->createUser();

        Sanctum::actingAs($user, [
            'user'
        ]);

        return $user;
    }

    public function authAdmin()
    {
        $admin = $this->createUser();

        Sanctum::actingAs($admin, [
            'admin'
        ]);

        return $admin;
    }

    public function createResource(array $args = [])
    {
        return Resource::factory()->create($args);
    }

    public function createSupport(array $args = [])
    {
        return Support::factory()->create($args);
    }

    public function createMotive(array $args = [])
    {
        return Motive::factory()->create($args);
    }

    public function createResourceVote(array $args = [])
    {
        return ResourceVote::factory()->create($args);
    }

    public function createReviews(int $quantity = 1, array $args = [])
    {
        return Review::factory($quantity)->create($args);
    }

    public function createReviewComplaint(array $args = [])
    {
        return ReviewComplaint::factory()->create($args);
    }

    public function createResourceComplaint(array $args = [])
    {
        return ResourceComplaint::factory()->create($args);
    }
}
