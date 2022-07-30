<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotiveSeeder extends Seeder
{
    private $motives = [
        [
            'name' => 'Fechado',
            'type' => 'resource_complaint'
        ],
        [
            'name' => 'Não existe aqui',
            'type' => 'resource_complaint'
        ],
        [
            'name' => 'Lugar duplicado',
            'type' => 'resource_complaint'
        ],
        [
            'name' => 'Ofensivo, prejudicial ou enganoso',
            'type' => 'resource_complaint'
        ],
        [
            'name' => 'Não está aberto ao público',
            'type' => 'resource_complaint'
        ],
        [
            'name' => 'Outro',
            'type' => 'resource_complaint'
        ],
        [
            'name' => 'Spam',
            'type' => 'review_complaint'
        ],
        [
            'name' => 'Linguagem Obscena',
            'type' => 'review_complaint'
        ],
        [
            'name' => 'Sem relação com o tópico',
            'type' => 'review_complaint'
        ],
        [
            'name' => 'Bullying ou assédio',
            'type' => 'review_complaint'
        ],
        [
            'name' => 'Discriminação ou discurso de ódio',
            'type' => 'review_complaint'
        ],
        [
            'name' => 'Informações pessoais',
            'type' => 'review_complaint'
        ],
        [
            'name' => 'Outro',
            'type' => 'review_complaint'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->motives as $motive) {
            $motive['created_at'] = now();
            $motive['updated_at'] = now();
            DB::table('motives')->insert($motive);
        }
    }
}
