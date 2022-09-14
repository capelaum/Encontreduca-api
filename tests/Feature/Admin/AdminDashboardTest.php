<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();

        $this->authAdmin();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_dashboard_index()
    {
        $this->getJson(route('admin.dashboard.index'))
            ->assertOk()
            ->assertJsonStructure([
                'usersCount',
                'resourcesCount',
                'approvedResourcesCount',
                'notApprovedResourcesCount',
                'resourceComplaintsCount',
                'resourceVotesCount',
                'approvedResourceVotesCount',
                'notApprovedResourceVotesCount',
                'reviewsCount',
                'reviewComplaintsCount',
                'categoriesCount',
                'motivesCount',
                'supportsCount'
            ]);
    }
}
