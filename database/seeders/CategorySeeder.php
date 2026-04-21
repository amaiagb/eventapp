<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Cultura', 'description' => 'Eventos culturales como museos, exposiciones, teatro, etc.', 'icon' => 'palette'],
            ['name' => 'Deporte', 'description' => 'Actividades deportivas y competiciones', 'icon' => 'trophy'],
            ['name' => 'Música', 'description' => 'Conciertos y festivales musicales', 'icon' => 'music'],
            ['name' => 'Gastronomía', 'description' => 'Eventos relacionados con comida y bebida', 'icon' => 'utensils'],
            ['name' => 'Tecnología', 'description' => 'Conferencias, hackathons y eventos tech', 'icon' => 'laptop'],
            ['name' => 'Arte', 'description' => 'Exposiciones de arte, talleres creativos', 'icon' => 'image'],
            ['name' => 'Educación', 'description' => 'Cursos, talleres y conferencias educativas', 'icon' => 'book'],
            ['name' => 'Ocio', 'description' => 'Eventos de entretenimiento y diversión', 'icon' => 'smile'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description'], 'icon' => $category['icon']]
            );
        }
    }
}
