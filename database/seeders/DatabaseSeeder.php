<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            MotiveSeeder::class,
            ResourceSeeder::class,
            ReviewSeeder::class,
            ReviewComplaintSeeder::class,
            ResourceComplaintSeeder::class,
            ResourceChangeSeeder::class,
            ResourceVoteSeeder::class,
            SupportSeeder::class,
            ResourceUserSeeder::class,
        ]);
    }
}
