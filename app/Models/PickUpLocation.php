<?php

namespace App\Models;

use App\Models\State;
use Ramsey\Uuid\Uuid;
use App\Models\WeightPricing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PickUpLocation extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'address', 'shipping_fee', 'state_id'];

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function weightPricing() {
        return $this->hasMany(WeightPricing::class, 'pickup_location_id');
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
