<?php

namespace Database\Seeders;

use App\Models\SupportRequest;
use Illuminate\Database\Seeder;

class SupportRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SupportRequest::factory(30)->create();
    }
}
