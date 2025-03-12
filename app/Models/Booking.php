<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'booking_date',
        'service_id',
        'total_price',
        'payment_status',
    ];

    // Definisikan relasi ke model Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
