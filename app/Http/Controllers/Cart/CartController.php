<?php

namespace App\Http\Controllers\Cart;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
// use App\Mail\VerifyMail;
// use App\Mail\RegisterMail;
use App\Models\Profile;
use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Cart\CartRepository;

class CartController extends Controller
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;

    }

    public function index(){

        return $this->cartRepository->index();
    }

    public function save(){

        return $this->cartRepository->create();
    }

    public function update($id){

        return $this->cartRepository->edit($id);
    }

    public function destroy($id){

        return $this->cartRepository->destroy($id);
    }

}
