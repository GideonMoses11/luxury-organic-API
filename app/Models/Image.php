<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'old_name', 'url', 'file_name', 'product_id'];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
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
