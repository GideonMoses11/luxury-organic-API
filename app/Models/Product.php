<?php

namespace App\Models;

use Log;
use App\Models\Tag;
use App\Models\Cart;
use App\Models\Image;
use App\Models\Video;
use Ramsey\Uuid\Uuid;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Category;
use App\Models\WishList;
use App\Models\PickUpLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'views'
    ];

    protected $with = ['images', 'categories', 'tags'];

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function tags(){
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function wish_lists(){
        return $this->hasMany(WishList::class);
    }

    public function userWishListed(){
        return $this->hasMany(WishList::class)->where('user_id', auth()->id());
    }

    // public function calculateWeightFee($pickupLocationId) {

    //     $pickupLocation = PickUpLocation::find($pickupLocationId);

    //     if ($pickupLocation){

    //         $weight = WeightPricing::where('pickup_location_id', $pickupLocationId)
    //             ->whereHas('weight', function ($query) {
    //                 $query->where('name', $this->weight); // Assuming 'name' represents the product's weight
    //             })->first();

    //         // ->where('weight', $this->weight)
    //             // ->first();

    //         if ($weight){
    //             return $weight;
    //         } else {
    //             return 0; // Or any default value you prefer when weight pricing is not found
    //         }
    //     }

    //     return null; // Handle scenario where pickup location is not found
    // }

    // public function calculateWeightFee($pickupLocationId) {
    //     $pickupLocation = PickupLocation::find($pickupLocationId);

    //     if ($pickupLocation) {
    //         $weight = WeightPricing::where('pickup_location_id', $pickupLocationId)
    //             ->where('weight', $this->weight)
    //             ->first();

    //         if ($weight) {
    //             return $weight->price;
    //         } else {
    //             // Log a message if weight pricing is not found
    //             Log::warning('Weight pricing not found for product ' . $this->id . ' at location ' . $pickupLocationId);
    //             return 0; // Or any default value you prefer when weight pricing is not found
    //         }
    //     }

    //     // Log a message if pickup location is not found
    //     \Log::warning('Pickup location not found: ' . $pickupLocationId);
    //     return null; // Handle scenario where pickup location is not found
    // }


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
