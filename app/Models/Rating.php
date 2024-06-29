<?php

namespace App\Models;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'scale', 'product_id', 'user_id'];

    protected $with = ['user'];

    public function product(){
        return $this->belongsTo(Product::class)->latest();
    }

    public function user(){
        return $this->belongsTo(User::class)->latest();
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
