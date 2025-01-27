<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelDestination extends Model
{
    use HasFactory;

    protected $table = 'travel_destinations';

    protected $fillable = [
        'id',
        'user_id',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
