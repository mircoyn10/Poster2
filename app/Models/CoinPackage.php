<?php

// app/Models/CoinPackage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    use HasFactory;

    // La tabella associata al model
    protected $table = 'coin_packages';

    // Campi mass-assignable
    protected $fillable = [
        'price',  // Prezzo del pacchetto
        'coins',  // Numero di monete offerte dal pacchetto
    ];
}
