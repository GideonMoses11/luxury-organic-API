<?php

namespace App\Models;

use App\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends BaseModel
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        // 'id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'address',
        'city',
        'state',
        'country',
        'profile_photo',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
