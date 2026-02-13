<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category',
        'unit',
        'farmer_id',
        'main_image',          // path to main image
        'additional_images',
    ];

    protected $casts = [
    'additional_images' => 'array',
    ];

    // Relationships
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getImageAttribute()
    {
        if (!empty($this->main_image)) {
            return $this->main_image;
        }

        if (is_array($this->additional_images) && count($this->additional_images) > 0) {
            return $this->additional_images[0];
        }

        return null;
    }

    public function getImageUrlAttribute()
    {
        $image = $this->image;
        if (!$image) {
            return null;
        }

        return route('media', ['path' => $image]);
    }

    public function reduceQuantity($quantity)
    {
        if ($this->quantity >= $quantity) {
            $this->decrement('quantity', $quantity);
            return true;
        }
        return false;
    }
}
