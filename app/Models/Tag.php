<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function products(){
        return $this->belongsToMany(Product::class, 'product_tag')->with('reviews', 'ratings');
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
