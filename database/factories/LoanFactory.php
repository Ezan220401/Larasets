<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $created_at = $this->faker->dateTimeBetween('-5 months');
        $loan_date = $this->faker->dateTimeBetween('now', '+3 months');
        $loan_length = $this->faker->dateTimeBetween($loan_date, $loan_date->format('Y-m-d H:i:s').' +3 months');

        return [
            'loan_name' => $this->faker->randomElement(['Peminjaman Ruangan', 'Peminjaman Barang', 'Peminjaman Alat', 'Peminjaman Kendaraan', 'Peminjaman Laboratorium']),
            'applicant_name' => $this->faker->name,
            'applicant_phone' => '62' . $this->faker->randomNumber(7, true),
            'applicant_position' => $this->faker->randomElement(['Ketua Organisasi', 'Mahasiswa Biasa', 'Sekertaris Organisasi', 'Dosen', 'Karyawan']),
            'applicant_number_id' => $this->faker->randomNumber(7, true),
            
            'loan_position' => $this->faker->randomElement(['Ditempat', 'Disuatu Tempat']),
            'loan_asset_name' => $this->faker->randomElement(['Komputer', 'Jeep', 'Arduino', 'Pen Display', 'Dryer', 'Mesin', 'Laptop', 'Kamera']) . ' ' . $this->faker->words(2, true),
            'loan_asset_quantity' => $this->faker->randomNumber(1, true),
            'loan_desc' => 'Data Dummy',
            'is_using' => true,
            'is_full_approve' => true,
            'loan_date' => $loan_date,
            'loan_length' => $loan_length,
            'loan_note_status' => $this->faker->text,

            'created_by' => $this->faker->randomNumber(1, true),
            'updated_by' => $this->faker->randomNumber(1, true),
            'created_at' => $created_at,
            'updated_at' => now(),
        ];
    }

}
