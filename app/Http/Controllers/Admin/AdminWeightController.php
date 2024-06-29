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
use App\Repositories\Admin\AdminWeightRepository;

class AdminWeightController extends Controller
{
    private $weightRepository;

    public function __construct(AdminWeightRepository $weightRepository)
    {
        $this->weightRepository = $weightRepository;

    }

    public function index(){

        return $this->weightRepository->listWeights();
    }

    public function save(){

        $data = request()->all();

        return $this->weightRepository->create($data);
    }

    public function update($id){

        $data = request()->all();

        return $this->weightRepository->edit($id);
    }

    public function find($id){

        return $this->weightRepository->show($id);
    }

    public function destroy($id){

        return $this->weightRepository->destroy($id);
    }

}
