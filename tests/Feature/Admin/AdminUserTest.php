<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    private string $avatarUrl = "https://res.cloudinary.com/capelaum/image/upload/v1661422020/encontreduca/avatars/pslj3lojhuzklk8fb9p6.jpg";
    private string $publicId = 'pslj3lojhuzklk8fb9p6';

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();
    }

    private array $userKeys = [
        'id',
        'name',
        'email',
        'avatarUrl',
        'reviewsCount',
        'resourcesCount',
        'savedResourcesCount',
        'votesCount'
    ];

    private array $showUserKeys = [
        'id',
        'name',
        'email',
        'avatarUrl',
        'resources',
        'savedResources',
        'reviews',
        'votes'
    ];

    public function test_admin_can_list_users()
    {
        $this->withExceptionHandling();

        $this->getJson(route('admin.users.index', ['search' => 'admin']))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->userKeys
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

    public function test_user_cannot_list_users()
    {
        $this->authUser();

        $this->createUser();

        $this->withExceptionHandling();

        $this->getJson(route('admin.users.index'))
            ->assertStatus(401)
            ->assertJsonStructure([
                'message'
            ]);
    }

    public function test_admin_can_show_user()
    {
        $this->getJson(route('admin.users.show', auth()->id()))
            ->assertOk()
            ->assertJsonStructure($this->showUserKeys)
            ->json();
    }

    public function test_admin_store_user()
    {
        $avatar = $this->createFakeImageFile('avatar.jpg');

        $user = User::factory()->make([
            'name' => 'John Doe',
            'email' => 'john_doe@email.com',
            'password' => '12345678',
            'avatar' => $avatar
        ]);

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($avatar->getRealPath(), [
                'folder' => 'encontreduca/avatars'
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->avatarUrl);

        $this->postJson(route('admin.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'confirmPassword' => $user->password,
            'avatar' => $user->avatar
        ])
            ->assertCreated()
            ->assertJsonStructure($this->showUserKeys)
            ->json();

    }

    public function test_admin_can_update_user()
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

        $response = $this->putJson(route('admin.users.update', Auth::id()), [
            'name' => $updatedUser->name,
            'email' => $updatedUser->email,
            'avatar' => $avatar,
            'password' => 'password',
            'confirmPassword' => 'password',
        ])->assertOk()
            ->assertJsonStructure($this->showUserKeys)
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

    public function test_admin_can_update_user_except_email_and_password()
    {
        $user = $this->createUser([
            'avatar_url' => $this->avatarUrl
        ]);

        $updatedUser = User::factory()->make();

        $avatar = $this->createFakeImageFile('avatar.jpg');

        $avatarUrlArray = explode('/', $this->avatarUrl);
        $publicId = explode('.', end($avatarUrlArray))[0];

        Cloudinary::shouldReceive('uploadFile')
            ->once()
            ->with($avatar->getRealPath(), [
                'folder' => 'encontreduca/avatars',
                'public_id' => $publicId
            ])
            ->andReturnSelf()
            ->shouldReceive('getSecurePath')
            ->once()
            ->andReturn($this->avatarUrl);

        $this->patchJson(route('admin.users.update', $user->id), [
            'name' => $updatedUser->name,
            'email' => $user->email,
            'avatar' => $avatar,
        ])->assertOk()
            ->assertJsonStructure($this->showUserKeys);

        $this->assertDatabaseHas('users', [
            'name' => $updatedUser->name,
            'email' => $user->email,
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $user = $this->createUser([
            'avatar_url' => $this->avatarUrl
        ]);

        $avatarUrlArray = explode('/', $this->avatarUrl);
        $publicId = explode('.', end($avatarUrlArray))[0];

        Cloudinary::shouldReceive('destroy')
            ->once()
            ->with("encontreduca/avatars/$publicId")
            ->andReturnSelf();

        $this->deleteJson(route('admin.users.destroy', $user->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_admin_can_delete_user_avatar()
    {
        $this->authAdmin([
            'avatar_url' => $this->avatarUrl
        ]);

        $avatarUrlArray = explode('/', $this->avatarUrl);
        $publicId = explode('.', end($avatarUrlArray))[0];

        Cloudinary::shouldReceive('destroy')
            ->once()
            ->with("encontreduca/avatars/$publicId")
            ->andReturnSelf();

        $this->deleteJson(route('admin.users.delete.avatar', Auth::id()))
            ->assertNoContent();

        $this->assertDatabaseHas('users', [
            'id' => Auth::id(),
            'avatar_url' => null
        ]);
    }
}
