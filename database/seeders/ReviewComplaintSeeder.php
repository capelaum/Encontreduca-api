<?php

namespace Database\Seeders;

use App\Models\ReviewComplaint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReviewComplaint::factory(20)->create();
    }
}
