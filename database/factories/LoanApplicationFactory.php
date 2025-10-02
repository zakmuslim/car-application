<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoanApplicationFactory extends Factory
{
    public function definition(): array
    {
        $vehicleTypes = ['car', 'ute', 'truck', 'van', 'suv'];
        $carMakes = ['Toyota', 'Honda', 'Mazda', 'Ford', 'Hyundai', 'Kia'];
        $carModels = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Hilux'],
            'Honda' => ['Civic', 'Accord', 'CR-V'],
            'Mazda' => ['Mazda3', 'CX-5', 'BT-50'],
            'Ford' => ['Ranger', 'Everest', 'Focus'],
            'Hyundai' => ['i30', 'Tucson', 'Santa Fe'],
            'Kia' => ['Cerato', 'Sportage', 'Sorento']
        ];

        $make = $this->faker->randomElement($carMakes);
        $purchasePrice = $this->faker->numberBetween(15000, 80000);
        
        return [
            'applicant_full_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'vehicle_type' => $this->faker->randomElement($vehicleTypes),
            'vehicle_make' => $make,
            'vehicle_model' => $this->faker->randomElement($carModels[$make]),
            'purchase_price' => $purchasePrice,
            'deposit_amount' => $this->faker->numberBetween(1000, $purchasePrice * 0.3),
            'term_months' => $this->faker->randomElement([36, 48, 60]),
            'status' => $this->faker->randomElement(['submitted', 'in_review', 'approved', 'declined']),
            'consent_to_credit_check' => true,
            'submitted_at' => $this->faker->dateTimeBetween('-3 months', 'now')
        ];
    }
}
