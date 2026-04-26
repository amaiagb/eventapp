<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Gratuito',
            'Familiar',
            'Outdoor',
            'Indoor',
            'Nocturno',
            'Accesible',
            'Premium',
            'Networking',
            'Workshop',
            'Exposición',
            'LGTBIQ+',
            'Feminista',
            'Salud Mental',
            'Inclusivo',
            'Discapacidad',
            'Inmigrantes',
            'Activismo',
            'Medioambiente',
            'Voluntariado',
            'Apoyo Mutuo',
            'Diversidad',
            'Igualdad',
            'Sostenibilidad',
            'Comunidad',
            'Jóvenes',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }
    }
}
