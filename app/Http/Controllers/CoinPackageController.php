<?php
// app/Http/Controllers/CoinPackageController.php
namespace App\Http\Controllers;

use App\Models\CoinPackage;
use Illuminate\Http\Request;

class CoinPackageController extends Controller
{
    // Restituisce tutti i pacchetti di monete
    public function index()
    {
        $coinPackages = CoinPackage::all();
        return response()->json($coinPackages);
    }
}

