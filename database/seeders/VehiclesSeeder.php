<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VehiclesSeeder extends Seeder
{
    public function run(): void
    {
        if (!Storage::disk('local')->exists('data/vehicles.json')) {
            Storage::disk('local')->put('data/vehicles.json', File::get(base_path('resources/data/vehicles.json')));
        }
    }
}
