<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_tmdb' => $this->faker->unique()->numberBetween(1000, 999999),
            'title' => $this->faker->sentence(3),
            'overview' => $this->faker->paragraph(),
            'poster_path' => '/poster_' . $this->faker->word() . '.jpg',
            'release_date' => $this->faker->date(),
            'genre_ids_tmdb' => json_encode([$this->faker->numberBetween(1, 50), $this->faker->numberBetween(1, 50)]),
        ];
    }
}
