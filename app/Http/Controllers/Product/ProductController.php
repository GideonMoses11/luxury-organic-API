<?php

namespace App\Http\Controllers\Product;

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
use App\Repositories\Product\ProductRepository;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

    }

    public function index(){

        return $this->productRepository->index();
    }

    // public function save(){

    //     // $data = request()->all();

    //     return $this->adminProductRepository->create();
    // }

    // public function update($id){

    //     $data = request()->all();

    //     return $this->adminCategoryRepository->editCategory($id);
    // }

    public function find($id){

        return $this->productRepository->find($id);
    }

    public function productWeightFees($id, $locationId){

        return $this->productRepository->productWeightFees($id, $locationId);
    }

    // public function destroy($id){

    //     return $this->adminCategoryRepository->destroyCategory($id);
    // }

}
