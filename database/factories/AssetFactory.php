<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'asset_name' => $this->faker->randomElement(['Komputer', 'Jeep', 'Arduino', 'Pen Display', 'Dryer', 'Mesin', 'Laptop', 'Kamera']) . ' ' . $this->faker->words(2, true),
            'asset_code' => $this->faker->unique()->word,
            'asset_type' => $this->faker->randomElement(['Type A', 'Type B', 'Type C']),
            'asset_desc' => $this->faker->text,
            'maintenance_desc' => $this->faker->text,
            'asset_position' => $this->faker->city,
            'category_id' => rand(1, 6),
            'asset_date_of_entry' => $this->faker->dateTime,
            'asset_quantity' => $this->faker->randomNumber(2),
            'asset_price' => $this->faker->randomNumber(5),
            'created_by' => 'Koordinator',
            'asset_code' => '000.00.00',
            'receipt_number' => rand(1, 9999),
            'updated_by' => null,
            'asset_image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}