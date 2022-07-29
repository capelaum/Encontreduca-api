<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    private $categories = [
        [
            'name' => 'Escola pública',
        ],
        [
            'name' => 'Escola privada',
        ],
        [
            'name' => 'Universidade pública',
        ],
        [
            'name' => 'Universidade privada',
        ],
        [
            'name' => 'Biblioteca',
        ],
        [
            'name' => 'Curso',
        ],
        [
            'name' => 'Coworking',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->categories as $category) {
            $category['created_at'] = now();
            $category['updated_at'] = now();
            DB::table('categories')->insert($category);
        }
    }
}
