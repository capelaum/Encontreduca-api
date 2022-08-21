<?php

namespace Tests;

use App\Models\Motive;
use App\Models\Resource;
use App\Models\ResourceVote;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ResourceSeeder;
use Database\Seeders\ReviewSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup(): void
    {
        parent::setup();

        $this->withoutExceptionHandling();

        User::factory(10)->create();
        $this->seed(CategorySeeder::class);
    }

    public function createResource(array $args = [])
    {
        return Resource::factory()->create($args);
    }

    public function createResourceVotes(int $quantity = 1, array $args = [])
    {
        return ResourceVote::factory($quantity)->create($args);
    }

    public function createReviews(int $quantity = 1, array $args = [])
    {
        return Review::factory($quantity)->create($args);
    }
}
