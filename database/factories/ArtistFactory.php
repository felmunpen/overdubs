<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ArtistFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'registered' => 0,
            'artist_pic' => 'https://images.squarespace-cdn.com/content/v1/5c8687d5af4683b3a8616498/1569609481199-V35Q6VH2XV0QS1ON39EV/Modern+Musician+Coach+Reviews',
            'user_id' => fake()->id(),
            'description' => Str::random(100),
            'info' => Str::random(100),
        ];
    }

}
