<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    // Definisikan relasi ke model Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
