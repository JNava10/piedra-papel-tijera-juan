<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hand>
 */
class HandFactory extends Factory
{
    public $hands = [
        'piedra' => 3,
        'papel' => 1,
        'tijera' => 2
    ];


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $hand = [
            'name' => array_key_first($this->hands),
            'beats' => $this->hands[array_key_first($this->hands)],
            'times_used' => 0
        ];

        array_shift($this->hands);

        return $hand;
    }
}
