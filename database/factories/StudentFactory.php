<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $genders = ['male', 'female'];
        $name = explode(' ',fake()->name());
        return [
            'first_name' => $name[0],
            'last_name' => $name[1],
            'image' => fake()->imageUrl(),
            'gender' => $genders[array_rand($genders)],
            'address' => fake()->text(),
            'contact_number' => fake()->randomNumber(),
            'birth_date' => fake()->date($format = 'Y-m-d', $max = 'now'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
