<?php

declare(strict_types=1);

namespace Modules\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Cms\Models\Home;

class HomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Home::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->word,
            'name' => $this->faker->name,
            'icon_src' => $this->faker->word,
            'created_by' => $this->faker->word,
            'updated_by' => $this->faker->word,
        ];
    }
}
