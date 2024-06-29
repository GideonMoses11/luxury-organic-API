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
use App\Repositories\Admin\PickUpLocationRepository;

class AdminPickUpLocationController extends Controller
{
    private $pickUpLocationRepository;

    public function __construct(PickUpLocationRepository $pickUpLocationRepository)
    {
        $this->pickUpLocationRepository = $pickUpLocationRepository;

    }

    public function index(){

        return $this->pickUpLocationRepository->listLocations();
    }

    public function save(){

        $data = request()->all();

        return $this->pickUpLocationRepository->create($data);
    }

    public function update($id){

        $data = request()->all();

        return $this->pickUpLocationRepository->edit($id);
    }

    public function find($id){

        return $this->pickUpLocationRepository->show($id);
    }

    public function destroy($id){

        return $this->pickUpLocationRepository->destroy($id);
    }

}
