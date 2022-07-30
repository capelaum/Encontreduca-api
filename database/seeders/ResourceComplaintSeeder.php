<?php

namespace Database\Seeders;

use App\Models\ResourceComplaint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ResourceComplaint::factory(20)->create();
    }
}
