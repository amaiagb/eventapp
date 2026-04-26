<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Nnjeim\World\Models\City;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios, categorías y tags
        $users = User::where('is_active', true)->get();
        $categories = Category::all();
        $tags = Tag::all();

        // Ciudades de España (nombres comunes para buscar)
        $spanishCities = [
            'Madrid',
            'Barcelona',
            'Valencia',
            'Sevilla',
            'Zaragoza',
            'Málaga',
            'Murcia',
            'Bilbao',
            'Leioa',
            'Tarragona'
        ];

        // Obtener IDs de ciudades españolas
        $cityIds = [];
        foreach ($spanishCities as $cityName) {
            $city = City::where('name', 'like', $cityName . '%')->first();
            if ($city) {
                $cityIds[] = $city->id;
            }
        }

        $events = [
            [
                'title' => 'Grupo de Apoyo LGTBIQ+',
                'description' => 'Espacio seguro de encuentro y apoyo para la comunidad LGTBIQ+. Compartimos experiencias y recursos.',
                'category' => 'Cultura',
                'city_index' => 0,
                'location' => 'Centro Cultural',
                'event_date' => Carbon::now()->addDays(15),
                'event_time' => '19:00',
                'max_attendees' => 25,
                'tags' => ['LGTBIQ+', 'Apoyo Mutuo', 'Inclusivo', 'Gratuito'],
            ],
            [
                'title' => 'Taller de Arte Accesible',
                'description' => 'Taller de pintura adaptado para personas con discapacidad. Materiales y espacio totalmente accesibles.',
                'category' => 'Arte',
                'city_index' => 1,
                'location' => 'Centro de Integración',
                'event_date' => Carbon::now()->addDays(20),
                'event_time' => '16:00',
                'max_attendees' => 15,
                'tags' => ['Discapacidad', 'Accesible', 'Workshop', 'Inclusivo', 'Gratuito'],
            ],
            [
                'title' => 'Círculo de Lectura Feminista',
                'description' => 'Lectura y debate de obras feministas. Espacio para reflexionar sobre igualdad de género.',
                'category' => 'Cultura',
                'city_index' => 2,
                'location' => 'Biblioteca Municipal',
                'event_date' => Carbon::now()->addDays(30),
                'event_time' => '18:00',
                'max_attendees' => 20,
                'tags' => ['Feminista', 'Igualdad', 'Networking', 'Gratuito'],
            ],
            [
                'title' => 'Grupo de Salud Mental',
                'description' => 'Reunión de apoyo para personas que buscan mejorar su bienestar emocional. Confidencialidad garantizada.',
                'category' => 'Educación',
                'city_index' => 3,
                'location' => 'Centro de Salud',
                'event_date' => Carbon::now()->addDays(10),
                'event_time' => '17:30',
                'max_attendees' => 12,
                'tags' => ['Salud Mental', 'Apoyo Mutuo', 'Inclusivo', 'Gratuito'],
            ],
            [
                'title' => 'Clase de Español para Inmigrantes',
                'description' => 'Clases gratuitas de español para personas recién llegadas. Integración y apoyo comunitario.',
                'category' => 'Educación',
                'city_index' => 4,
                'location' => 'Asociación de Vecinos',
                'event_date' => Carbon::now()->addDays(25),
                'event_time' => '10:00',
                'max_attendees' => 15,
                'tags' => ['Inmigrantes', 'Comunidad', 'Workshop', 'Gratuito'],
            ],
            [
                'title' => 'Limpiada de Playa Voluntaria',
                'description' => 'Jornada de limpieza de la playa con voluntarios locales. Protegemos nuestro entorno.',
                'category' => 'Medioambiente',
                'city_index' => 5,
                'location' => 'Playa',
                'event_date' => Carbon::now()->addDays(12),
                'event_time' => '09:00',
                'max_attendees' => 50,
                'tags' => ['Medioambiente', 'Voluntariado', 'Outdoor', 'Sostenibilidad', 'Gratuito'],
            ],
            [
                'title' => 'Marcha por el Clima',
                'description' => 'Manifestación pacífica para exigir acción climática. Únete al movimiento ambientalista local.',
                'category' => 'Activismo',
                'city_index' => 6,
                'location' => 'Plaza Mayor',
                'event_date' => Carbon::now()->addDays(18),
                'event_time' => '11:00',
                'max_attendees' => 200,
                'tags' => ['Activismo', 'Medioambiente', 'Jóvenes', 'Outdoor', 'Gratuito'],
            ],
            [
                'title' => 'Taller de Huerto Urbano',
                'description' => 'Aprende a cultivar tus propios alimentos en espacios pequeños. Agricultura urbana sostenible.',
                'category' => 'Medioambiente',
                'city_index' => 7,
                'location' => 'Huerto Comunitario',
                'event_date' => Carbon::now()->addDays(22),
                'event_time' => '10:00',
                'max_attendees' => 20,
                'tags' => ['Medioambiente', 'Sostenibilidad', 'Workshop', 'Outdoor', 'Gratuito'],
            ],
            [
                'title' => 'Café de Encuentro Intercultural',
                'description' => 'Encuentro informal entre personas de diferentes culturas. Compartimos comida y experiencias.',
                'category' => 'Cultura',
                'city_index' => 8,
                'location' => 'Centro Cívico',
                'event_date' => Carbon::now()->addDays(35),
                'event_time' => '16:00',
                'max_attendees' => 30,
                'tags' => ['Inmigrantes', 'Diversidad', 'Comunidad', 'Networking', 'Gratuito'],
            ],
            [
                'title' => 'Taller de Empoderamiento Joven',
                'description' => 'Actividades para desarrollar liderazgo y autoestima en jóvenes. Espacio seguro y diverso.',
                'category' => 'Educación',
                'city_index' => 9,
                'location' => 'Centro Juvenil',
                'event_date' => Carbon::now()->addDays(28),
                'event_time' => '17:00',
                'max_attendees' => 25,
                'tags' => ['Jóvenes', 'Activismo', 'Workshop', 'Inclusivo', 'Gratuito'],
            ],
        ];

        foreach ($events as $eventData) {
            $category = $categories->where('name', $eventData['category'])->first();
            $user = $users->random();
            // Usar módulo para repetir ciudades si hay más eventos que ciudades
            $cityId = $cityIds[$eventData['city_index'] % count($cityIds)];

            $event = Event::updateOrCreate(
                [
                    'title' => $eventData['title'],
                    'event_date' => $eventData['event_date'],
                ],
                [
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'city_id' => $cityId,
                    'description' => $eventData['description'],
                    'event_time' => $eventData['event_time'],
                    'location' => $eventData['location'],
                    'cover_image' => 'default.png',
                    'max_attendees' => $eventData['max_attendees'],
                    'status' => 'approved',
                    'rejection_reason' => null,
                ]
            );

            // Asignar tags al evento
            if (isset($eventData['tags']) && is_array($eventData['tags'])) {
                foreach ($eventData['tags'] as $tagName) {
                    $tag = $tags->where('name', $tagName)->first();
                    if ($tag) {
                        $event->tags()->syncWithoutDetaching($tag->id);
                    }
                }
            }
        }
    }
}
