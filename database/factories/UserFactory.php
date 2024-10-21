<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;
    
    public function definition()
    {
        static $increment = 1;

        return [
            'user_name' => 'user_' . $increment,
            'user_email' => 'user_' . $increment. '@example.com',
            'user_photo' => $this->faker->imageUrl(),
            'user_number_id' => $this->faker->numerify('##########'),
            'user_email' => $this->faker->unique()->safeEmail,
            'user_password' => Hash::make('password'),
            'user_phone' => $this->faker->phoneNumber,
            'group_id' => 7,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'user_name' => $this->faker->name,
            'user_photo' => $this->faker->imageUrl(),
            'user_number_id' => $this->faker->numerify('##########'),
            'user_email' => $this->faker->unique()->safeEmail,
            'user_password' => Hash::make('password'),
            'user_phone' => $this->faker->phoneNumber,
            'group_id' => rand(1, 11),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
