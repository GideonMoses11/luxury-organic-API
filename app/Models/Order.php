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

    protected $fillable = ['id', 'total_quantity', 'total_amount', 'status', 'delivery_address', 'pickup_location_id', 'state_id', 'user_id'];

    protected $with = ['state', 'pickupLocation', 'products', 'user', 'payment'];

    public function products(){
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function pickupLocation(){
        return $this->belongsTo(PickUpLocation::class);
    }

    // public function transactions(){
    //     return $this->HasMany(Transaction::class, 'order_id');
    // }

    public function payment(){
        return $this->HasOne(Payment::class, 'order_id');
    }

    public function calculateWEIGHTFee($orderWeight, $stateId, $locationId)
    {
        // Retrieve weight pricing based on product weight and state
        $weightPricing = WeightPricing::where('weight', $orderWeight)
            ->where('state_id', $stateId)
            ->orWhere('pickup_location_id', $locationId)
            ->first();

        if ($weightPricing) {
            return $weightPricing->shipping_fee;
        }

        // If no specific weight-based pricing for the state is found,
        // you might consider fallback options, such as default pricing.
        // For instance, you might have a default shipping fee if there's no specific weight pricing for the state.
        $defaultWeightPricing = WeightPricing::where('weight', $orderWeight)
            ->where('state_id', null) // Assuming this is where the default shipping fee is stored
            ->first();

        if ($defaultWeightPricing) {
            return $defaultWeightPricing->shipping_fee;
        }

        // Return null or a default value indicating no matching pricing found
        return null;
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
