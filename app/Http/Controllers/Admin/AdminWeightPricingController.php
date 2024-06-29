<?php

namespace App\Http\Controllers\Admin;

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
use App\Repositories\Admin\WeightPricingRepository;

class AdminWeightPricingController extends Controller
{
    private $weightPricingRepository;

    public function __construct(WeightPricingRepository $weightPricingRepository)
    {
        $this->weightPricingRepository = $weightPricingRepository;

    }

    public function index(){

        return $this->weightPricingRepository->index();
    }

    public function save(){

        $data = request()->all();

        return $this->weightPricingRepository->create($data);
    }

    public function update($id){

        $data = request()->all();

        return $this->weightPricingRepository->edit($id);
    }

    public function find($id){

        return $this->weightPricingRepository->show($id);
    }

    public function destroy($id){

        return $this->weightPricingRepository->destroy($id);
    }

}
