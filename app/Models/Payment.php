<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'ref_no', 'tx_ref', 'name', 'email', 'phone', 'description',
                            'amount', 'payment_options', 'currency', 'channel',
                            'status', 'order_id', 'user_id'];

    // protected $with = ['user', 'order'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
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
