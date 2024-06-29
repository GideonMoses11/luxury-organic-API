<?php

namespace App\Models;

use App\Models\State;
use Ramsey\Uuid\Uuid;
use App\Models\Weight;
use App\Models\PickUpLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeightPricing extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'price',
        'weight_id',
        'pickup_location_id',
        'state_id',
    ];

    protected $with = ['weight', 'state', 'pickupLocation'];

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function weight(){
        return $this->belongsTo(Weight::class);
    }

    public function pickupLocation() {
        return $this->belongsTo(PickUpLocation::class, 'pickup_location_id');
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
