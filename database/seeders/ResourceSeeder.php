<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    private $resources = [
        [
            'name' => 'Escola Classe 405 Norte',
            'category_id' => 1,
            'user_id' => 1,
            'latitude' => -15.7708927,
            'longitude' => -47.8769279,
            'address' => 'SHCN SQN 405 - Asa Norte, Brasília - DF, 70846-000',
            'website' => null,
            'phone' => '(61) 3273-2972',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => true
        ],
        [
            'name' => 'Centro Educacional Leonardo da Vinci',
            'category_id' => 2,
            'user_id' => 1,
            'latitude' => -15.7467763,
            'longitude' => -47.9008108,
            'address' => 'SGAN 914 Conjunto I Setor de Grandes Áreas Norte, Asa Norte, Brasília - DF, 70790-140',
            'website' => 'https://www.leonardoonline.com.br',
            'phone' => '(61) 3340-1616',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => true
        ],
        [
            'name' => 'Universidade de Brasília',
            'category_id' => 3,
            'user_id' => 1,
            'latitude' => -15.7631573,
            'longitude' => -47.8706311,
            'address' => 'UnB - Brasília, DF, 70910-900',
            'website' => 'http://www.unb.br',
            'phone' => '(61) 3107-3300',
            'cover' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Campus_Darcy_Ribeiro_%2831398447555%29.jpg/1200px-Campus_Darcy_Ribeiro_%2831398447555%29.jpg',
            'approved' => true
        ],
        [
            'name' => 'CEUB',
            'category_id' => 4,
            'user_id' => 1,
            'latitude' => -15.7670265,
            'longitude' => -47.8938074,
            'address' => '707/907 - Campus Universitário - Asa Norte, Brasília - DF, 70790-075',
            'website' => 'https://www.uniceub.br',
            'phone' => '(61) 3966-1201',
            'cover' => 'https://cdn.jornaldebrasilia.com.br/wp-content/uploads/2019/11/WhatsApp-Image-2019-10-31-at-16.41.03-2.jpeg',
            'approved' => true
        ],
        [
            'name' => 'Biblioteca Central',
            'category_id' => 5,
            'user_id' => 1,
            'latitude' => -15.7609124,
            'longitude' => -47.8677803,
            'address' => 'Campus Universitário Darcy Ribeiro, Gleba A - Asa Norte, Brasília - DF, 70910-900',
            'website' => 'http://www.bce.unb.br',
            'phone' => '(61) 3107-2676',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => true
        ],
        [
            'name' => 'Curso Exatas',
            'category_id' => 6,
            'user_id' => 1,
            'latitude' => -15.7747056,
            'longitude' => -47.8887548,
            'address' => 'SCRN 704/705 Bloco A, 53 - Asa Norte, Brasília - DF, 70730-610',
            'website' => 'https://www.cursoexatas.com.br',
            'phone' => '(61) 3242-0628',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => true
        ],
        [
            'name' => 'The Brain Coworking',
            'category_id' => 7,
            'user_id' => 1,
            'latitude' => -15.7880305,
            'longitude' => -47.9005331,
            'address' => 'Ulysses Guimarães SDC, Bloco I, Zona Cívico e Administrativa- Ala Norte Piso 01, Eixo Monumental, Brasília - DF, 70655-775',
            'website' => 'http://thebraincoworking.com.br',
            'phone' => '(61) 3142-0106',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => true
        ],
        [
            'name' => 'Colégio Pódion',
            'category_id' => 2,
            'user_id' => 1,
            'latitude' => -15.75035,
            'longitude' => -47.8992081,
            'address' => 'SHCGN 713 ÁREA ESPECIAL',
            'website' => 'http://www.podion.com.br',
            'phone' => '(61) 3042-3849',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => false
        ],
        [
            'name' => 'Centro Educacional Sigma',
            'category_id' => 2,
            'user_id' => 1,
            'latitude' => -15.8174547,
            'longitude' => -47.9188311,
            'address' => 'SGAS I SGAS 912 - Asa Sul, Brasília - DF, 70390-120',
            'website' => 'https://sigmadf.com.br',
            'phone' => '(61) 3042-3849',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => false
        ],
        [
            'name' => 'Pódion',
            'category_id' => 6,
            'user_id' => 1,
            'latitude' => -15.7543684,
            'longitude' => -47.8986737,
            'address' => '2º andar SHCGN, Quadra 712, Conjunto B s/n - Asa Norte, DF, 70360-702',
            'website' => 'http://www.podion.com.br',
            'phone' => '(61) 3272-7742',
            'cover' => 'https://dummyimage.com/380x200/333/fff',
            'approved' => false
        ]
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->resources as $resource) {
            $resource['created_at'] = now();
            $resource['updated_at'] = now();
            DB::table('resources')->insert($resource);
        }
    }
}
