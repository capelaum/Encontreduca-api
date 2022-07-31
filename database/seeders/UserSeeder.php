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
            'avatar_url' => 'https://res.cloudinary.com/capelaum/image/upload/v1648581498/admin-uploads/xgeusezbgvtxuit2fn5e.jpg',
        ]);

        User::factory(19)->create();
    }
}
