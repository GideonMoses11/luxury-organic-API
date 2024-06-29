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
use App\Repositories\Admin\AdminProductRepository;

class AdminProductController extends Controller
{
    private $adminProductRepository;

    public function __construct(AdminProductRepository $adminProductRepository)
    {
        $this->adminProductRepository = $adminProductRepository;

    }

    public function dashboard(){

        return $this->adminProductRepository->dashboard();
    }

    public function index(){

        return $this->adminProductRepository->index();
    }

    public function save(){

        // $data = request()->all();

        return $this->adminProductRepository->create();
    }

    public function update($id){

        // $data = request()->all();

        return $this->adminProductRepository->edit($id);
    }

    public function find($id){

        return $this->adminProductRepository->find($id);
    }

    public function destroy($id){

        return $this->adminProductRepository->destroy($id);
    }

}
