<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\BaseModel;
use App\Models\Cart;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Profile;
use App\Models\WishList;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends BaseModel implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'id',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $with = ['profile', 'wish_lists', 'cart'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function nameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function profile(){
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function wish_lists(){
        return $this->hasMany(WishList::class);
    }
}
