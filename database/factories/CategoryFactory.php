<?php

namespace Database\Factories;

use App\Enums\CategoryGroup;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'parent_id' => null,
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(4),
            'group' => fake()->randomElement(CategoryGroup::cases()),
            'description' => fake()->paragraph(),
            'cover_image' => null,
            'banner_image' => null,
            'show_on_homepage' => fake()->boolean(40),
            'sort_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }

    public function kanjeevaram(): static
    {
        return $this->state(fn () => [
            'group' => CategoryGroup::Kanjeevaram,
        ]);
    }

    public function weave(): static
    {
        return $this->state(fn () => [
            'group' => CategoryGroup::Weave,
        ]);
    }

    public function fabric(): static
    {
        return $this->state(fn () => [
            'group' => CategoryGroup::Fabric,
        ]);
    }

    public function occasion(): static
    {
        return $this->state(fn () => [
            'group' => CategoryGroup::Occasion,
        ]);
    }
}
