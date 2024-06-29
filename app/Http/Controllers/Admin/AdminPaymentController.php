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
use App\Repositories\Admin\AdminPaymentRepository;

class AdminPaymentController extends Controller
{
    private $adminPaymentRepository;

    public function __construct(AdminPaymentRepository $adminPaymentRepository)
    {
        $this->adminPaymentRepository = $adminPaymentRepository;

    }

    public function index(){

        return $this->adminPaymentRepository->index();
    }

    public function find($id){

        return $this->adminPaymentRepository->show($id);
    }

}
