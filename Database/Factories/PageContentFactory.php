<?php

declare(strict_types=1);

namespace Modules\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Cms\Models\PageContent>
 */
class PageContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Cms\Models\PageContent::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
