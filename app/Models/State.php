<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\WeightPricing;
use App\Models\PickUpLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'name'];

    protected $with = ['pickupLocations'];

    public function pickupLocations(){
        return $this->hasMany(PickUpLocation::class);
    }

    public function pickupLocation(){
        return $this->hasOne(PickUpLocation::class);
    }

    public function weightPricings(){
        return $this->hasMany(WeightPricing::class);
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
