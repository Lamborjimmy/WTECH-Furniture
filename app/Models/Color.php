<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'hex_string',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}