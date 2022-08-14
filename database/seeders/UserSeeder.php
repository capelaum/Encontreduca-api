<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'luis@email.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'avatar_url' => null,
        ]);

        User::factory(19)->create();
    }
}
