<?php

namespace Database\Seeders;

use App\Models\ResourceVote;
use Illuminate\Database\Seeder;

class ResourceVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResourceVote::factory(900)->create();
    }
}
