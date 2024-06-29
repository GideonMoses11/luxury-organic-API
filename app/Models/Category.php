<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        // 'id',
        'category_name',
        'slug',
        'icon_image',
    ];


    public function products(){
        return $this->belongsToMany(Product::class, 'category_product')->with('reviews', 'ratings');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUuid();
        });
    }

    public static function generateUuid()
    {
        return Uuid::uuid4()->toString();
    }
}
