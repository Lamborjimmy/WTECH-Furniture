<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'street_and_number',
        'city',
        'post_code',
        'country',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}