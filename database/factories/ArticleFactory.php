<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'title' => $this->faker->sentence(6), // Заголовок из 6 слов
            'body' => $this->faker->paragraph(10), // Текст из 10 параграфов
            'category_id' => \App\Models\Category::factory(), // Создаст новую категорию
        ];
    }
}
