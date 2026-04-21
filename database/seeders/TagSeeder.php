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
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['name' => $tag]);
        }
    }
}
