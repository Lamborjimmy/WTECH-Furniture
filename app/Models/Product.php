<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'color_id', 'category_id', 'material_id',
        'placement_id', 'price', 'in_stock', 'valid', 'width', 'length',
        'depth', 'added_date', 'modified_date', 'code'
    ];

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    public function images()
    {
        return $this->hasMany(ImageReference::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ImageReference::class)->where('is_main', true);
    }
}