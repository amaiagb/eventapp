<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'city_id' => City::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'event_date' => fake()->date(),
            'event_time' => fake()->time(),
            'location' => fake()->address(),
            'max_attendees' => fake()->numberBetween(10, 500),
            'status' => 'pending',
        ];
    }
}
