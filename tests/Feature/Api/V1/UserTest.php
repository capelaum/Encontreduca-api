<?php

namespace Tests\Feature\Api\V1;

use App\Http\Requests\V1\UpdateUserRequest;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private string $avatarUrl = "https://res.cloudinary.com/capelaum/image/upload/v1661422020/encontreduca/avatars/pslj3lojhuzklk8fb9p6.jpg";
    private string $publicId = 'pslj3lojhuzklk8fb9p6';

    public function setup(): void
    {
        parent::setup();

        $this->authUser();
    }

    private array $userKeys = [
        'id',
        'name',
        'avatarUrl',
        'reviewCount',
        'resourcesIds'
    ];

    public function test_list_users()
    {
        $this->authAdmin();

        $this->getJson(route('users.index'))
            ->assertOk()
            ->assertJsonStructure(['*' => $this->userKeys])
            ->json();
    }

    public function test_user_cannot_list_users()
    {
        $this->createUser();

        $this->withExceptionHandling();

        $this->getJson(route('users.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_show_user()
    {
        $this->getJson(route('users.show', Auth::id()))
            ->assertOk()
            ->assertJsonStructure($this->userKeys)
            ->json();
    }

    public function test_user_cannot_show_other_user_data()
    {
        $user = $this->createUser();

        $this->withExceptionHandling();

        $this->getJson(route('users.show', $user->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_update_user()
    {
        $updatedUser = User::factory()->make();

        $avatar = $this->createFakeImageFile('avatar.jpg');

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($avatar->getRealPath(), [
                'folder' => 'encontreduca/avatars'
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->avatarUrl);

        $response = $this->putJson(route('users.update', Auth::id()), [
            'name' => $updatedUser->name,
            'email' => $updatedUser->email,
            'avatar' => $avatar,
            'password' => 'password',
            'confirmPassword' => 'password',
        ])->assertOk()
            ->assertJsonStructure($this->userKeys)
            ->json();

        $this->assertDatabaseHas('users', [
            'name' => $updatedUser->name,
            'email' => Auth::user()->email,
            'avatar_url' => $response['avatarUrl'],
            'email_verified_at' => date($updatedUser->email_verified_at),
        ]);

        // assert that new email is stores in pending user emails table
        $this->assertDatabaseHas('pending_user_emails', [
            'user_id' => Auth::id(),
            'user_type' => 'App\Models\User',
            'email' => $updatedUser->email,
        ]);

        // assert that new password matches the auth user password
        $this->assertTrue(Hash::check('password', Auth::user()->getAuthPassword()));
    }

    public function test_update_user_with_patch_request()
    {
        $user = $this->authUser([
            'avatar_url' => $this->avatarUrl
        ]);

        $avatar = $this->createFakeImageFile('avatar.jpg');

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($avatar->getRealPath(), [
                'folder' => 'encontreduca/avatars',
                'public_id' => $this->publicId,
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->avatarUrl);

        $response = $this->patchJson(route('users.update', $user->id), [
            'name' => 'updated Name',
            'avatar' => $avatar,
        ])->assertOk()
            ->assertJsonStructure($this->userKeys)->json();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'updated Name',
            'avatar_url' => $response['avatarUrl'],
            'email_verified_at' => date($user->email_verified_at),
        ]);
    }

    public function test_update_user_except_email_and_password()
    {
        $updatedUser = User::factory()->make();

        $this->patchJson(route('users.update', Auth::id()), [
            'name' => $updatedUser->name,
            'email' => Auth::user()->email,
        ])->assertOk()
            ->assertJsonStructure($this->userKeys);

        $this->assertDatabaseHas('users', [
            'name' => $updatedUser->name,
            'email' => Auth::user()->email,
        ]);
    }

    public function test_user_cannot_update_other_user()
    {
        $this->withExceptionHandling();

        $updatedUser = $this->createUser(['password' => 'password']);

        $this->patchJson(route('users.update', $updatedUser->id), [
            'name' => $updatedUser->name,
            'email' => $updatedUser->email,
            'password' => $updatedUser->password,
            'confirmPassword' => $updatedUser->password
        ])->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_delete_user()
    {
        $this->deleteJson(route('users.destroy', Auth::id()))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => Auth::id()
        ]);
    }

    public function test_user_cannot_delete_other_user()
    {
        $this->withExceptionHandling();

        $user = $this->createUser();

        $this->deleteJson(route('users.destroy', $user->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_delete_user_avatar()
    {
        $this->authUser([
            'avatar_url' => $this->avatarUrl
        ]);

        $this->deleteJson(route('users.delete.avatar', Auth::id()))
            ->assertNoContent();

        $this->assertDatabaseHas('users', [
            'id' => Auth::id(),
            'avatar_url' => null
        ]);
    }

    public function test_user_cannot_delete_other_user_avatar()
    {
        $this->withExceptionHandling();

        $user = $this->createUser();

        $this->deleteJson(route('users.delete.avatar', $user->id))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_user_votes()
    {
        $this->createResourceUser(['user_id' => Auth::id()]);

        $this->getJson(route('users.votes'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'userId',
                    'resourceId',
                    'vote',
                    'justification'
                ]
            ]);
    }

    private function mockCloudinaryEngine(File $avatar)
    {
        $this->mock(CloudinaryEngine::class, function ($mock) use ($avatar) {
            $mock->shouldReceive('getSecurePath')
                ->once()
                ->andReturn($this->avatarUrl);
        });
    }
}

