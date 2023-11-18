<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


            return [
                'title' =>Faker::sentence(1),
                'content'=>Faker::paragraph(3),
                'author_id'=>1,
                'meta_title'=>Faker::sentence(3),
                'meta_description'=>Faker::sentence(6),
                'shortlink'=>url("/articles",\Str::Random(8)),
                'show_at_popular'=>fake()->boolean(),
                'archive'=>fake()->boolean(),
                'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                'updated_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ];

    }
}
