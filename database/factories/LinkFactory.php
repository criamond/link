<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Link;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Создает пользователя автоматически
            'original_url' => $this->faker->url(),
            'short_code' => $this->faker->unique()->regexify('[A-Za-z0-9]{6}'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
