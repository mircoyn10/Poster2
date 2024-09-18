<?php

// app/Models/Purchase.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // La tabella associata al modello
    protected $table = 'purchases';

    // Campi mass-assignable
    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'coins',
        'status',
    ];

    // Relazione con l'utente
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
