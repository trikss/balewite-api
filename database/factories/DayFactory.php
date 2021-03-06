<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'day' => $this->faker->unique()->dayOfWeek()
        ];
    }
}
