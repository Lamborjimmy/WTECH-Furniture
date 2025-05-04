<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageReference extends Model
{
    use HasFactory;
    
    protected $fillable = ['product_id', 'title', 'path', 'is_main'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
