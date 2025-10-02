<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class VehiclesController extends Controller
{
    public function index()
    {
        $json = Storage::disk('local')->get('data/vehicles.json');
        return response()->json(json_decode($json, true));
    }
}
