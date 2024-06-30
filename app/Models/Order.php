<?php

namespace App\Models;

use App\Models\User;
use App\Models\State;
use Ramsey\Uuid\Uuid;
use App\Models\Payment;
use App\Models\Product;
use App\Models\WeightPricing;
use App\Models\PickUpLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'total_quantity', 'total_amount', 'status', 'delivery_address', 'city', 'state', 'country', 'user_id'];

    protected $with = ['products', 'user', 'payment'];

    public function products(){
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function transactions(){
    //     return $this->HasMany(Transaction::class, 'order_id');
    // }

    public function payment(){
        return $this->HasOne(Payment::class, 'order_id');
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
