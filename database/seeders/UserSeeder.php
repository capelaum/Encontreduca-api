<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Luís V. Capelletto',
            'email' => 'luis@emil.com',
            'password' => bcrypt('secret'),
            'email_verified_at' => now(),
            'avatar_url' => 'https://i.pravatar.cc/300?u=luis',
        ]);

        User::factory(19)->create();
    }
}
