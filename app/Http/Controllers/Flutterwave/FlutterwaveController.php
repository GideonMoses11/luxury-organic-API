<?php

namespace App\Http\Controllers\Flutterwave;

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
use App\Repositories\Flutterwave\FlutterwaveRepository;

class FlutterwaveController extends Controller
{
    private $flutterwaveRepository;

    public function __construct(FlutterwaveRepository $flutterwaveRepository)
    {
        $this->flutterwaveRepository = $flutterwaveRepository;

    }

    public function initialize(){

        return $this->flutterwaveRepository->initialize();
    }

    public function webhook(){

        $data = request()->all();

        return $this->flutterwaveRepository->webhook($data);
    }

    public function callback(){

        return $this->flutterwaveRepository->callback();
    }

}
