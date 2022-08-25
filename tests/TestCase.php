<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Database\Seeders\{ReviewSeeder, CategorySeeder, MotiveSeeder};
use App\Models\{ResourceUser,
    User,
    Motive,
    Resource,
    ResourceChange,
    ResourceComplaint,
    ResourceVote,
    Review,
    ReviewComplaint,
    Support
};

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public Collection $userIdsWithoutAuthUser;

    public function setup(): void
    {
        parent::setup();

        $this->withoutExceptionHandling();

        $this->seed(CategorySeeder::class);
        $this->seed(MotiveSeeder::class);

        User::factory(10)->create();
        Resource::factory(10)->create();
        Review::factory(10)->create();

        $this->userIdsWithoutAuthUser = collect(User::all()->modelKeys());
    }

    public function createUser(array $args = [])
    {
        return User::factory()->create($args);
    }

    public function authUser(array $args = [])
    {
        $user = $this->createUser($args);

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

    public function createResourceChange(array $args = [])
    {
        return ResourceChange::factory()->create($args);
    }

    public function createResourceUser(array $args = [])
    {
        return ResourceUser::factory()->create($args);
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

    public function createReview(array $args = [])
    {
        return Review::factory()->create($args);
    }

    public function createReviewComplaint(array $args = [])
    {
        return ReviewComplaint::factory()->create($args);
    }

    public function createResourceComplaint(array $args = [])
    {
        return ResourceComplaint::factory()->create($args);
    }

    public function createPasswordResetToken(User $user): string
    {
        return Password::broker()->createToken($user);
    }

    public function createFakeImageFile(string $name): File
    {
        return File::fake()->image($name);
    }
}
