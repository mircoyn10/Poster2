<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'prompt', 'response'];

    // Definisci la relazione con il modello User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

