<?php
namespace Database\Seeders;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'consultant@example.com')->exists()) {
            User::create([
                'name' => 'Consultant',
                'email' => 'consultant@example.com',
                'password' => Hash::make('password'),
                'role' => 'consultant'
            ]);
        }
        if (!User::where('email', 'applicant@example.com')->exists()) {
            User::create([
                'name' => 'Applicant',
                'email' => 'applicant@example.com',
                'password' => Hash::make('password'),
                'role' => 'applicant'
            ]);
        }

        // Generate sample loan applications
        if (LoanApplication::count() === 0) {
            // Create a few loans for the demo applicant
            LoanApplication::factory()->count(2)->create([
                'email' => 'applicant@example.com',
                'status' => 'submitted',
                'submitted_at' => now()->subDays(rand(1, 5))
            ]);

            // Create a variety of loans with different statuses
            LoanApplication::factory()->count(15)->create([
                'submitted_at' => now()->subDays(rand(1, 30))
            ]);

            // Create some very recent applications
            LoanApplication::factory()->count(3)->create([
                'status' => 'submitted',
                'submitted_at' => now()->subHours(rand(1, 12))
            ]);
        }
    }
}
