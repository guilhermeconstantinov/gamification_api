<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LuckyNumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => str_pad(strval($this->faker->unique()->numberBetween(000000, 999999)) , 6 , '0' , STR_PAD_LEFT),
            'drawn' => false,
            'date' => Carbon::now()
        ];
    }

}
