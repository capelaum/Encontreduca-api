<?php

namespace Database\Seeders;

use App\Models\ResourceUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= User::count(); $i++) {
            DB::table('resource_user')->insert([
                'user_id' =>  $i,
                'resource_id' =>  $i,
            ]);
        }
    }
}
