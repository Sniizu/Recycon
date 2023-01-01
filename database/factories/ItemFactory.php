<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'id' => Item::generateId(),
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(5000, 100000),
            'image' => "https://picsum.photos/seed/".$this->faker->unique()->word."/640/480/",
            'category' => ['Recycle','Second'][rand(0,1)],
            'description' => $this->faker->sentence(rand(10, 20))
        ];
    }
}
