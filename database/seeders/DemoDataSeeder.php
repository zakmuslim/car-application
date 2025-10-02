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

        // optional: generate sample loans if you add a factory; otherwise skip
    }
}
