<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_available', true)->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        if (file_exists(public_path('images/'.$this->slug.'.jpg'))) {
            return asset('images/'.$this->slug.'.jpg');
        }

        return asset('images/hero-banner.jpg');
    }
}
