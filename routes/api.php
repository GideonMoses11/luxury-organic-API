<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\State\StateController;
use App\Http\Controllers\Rating\RatingController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Stripe\StripeController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminStateController;
use App\Http\Controllers\Admin\AdminWeightController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\WishList\WishListController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Flutterwave\FlutterwaveController;
use App\Http\Controllers\Admin\AdminWeightPricingController;
use App\Http\Controllers\Admin\AdminPickUpLocationController;
use App\Http\Controllers\PickUpLocation\PickUpLocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::post('/signin', [AuthController::class, 'signin']);
    Route::post('/logout', [AuthController::class, 'signout']);
    Route::get('/account', [AuthController::class, 'userAccount']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-profile-photo', [AuthController::class, 'updateProfilePhoto']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

//categories
Route::group(['prefix' => 'categories'], function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'find']);
});

//tags
Route::group(['prefix' => 'tags'], function () {
    Route::get('', [TagController::class, 'index']);
    Route::get('/{id}', [TagController::class, 'find']);
});

Route::group(['prefix' => 'products'], function () {
    Route::get('', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'find']);
    Route::get('/{id}/{locationId}', [ProductController::class, 'productWeightFees']);
});

Route::group(['prefix' => 'reviews'], function () {
    Route::post('/add', [ReviewController::class, 'save']);
    Route::post('/update/{id}', [ReviewController::class, 'update']);
    Route::post('/delete/{id}', [ReviewController::class, 'destroy']);
});

Route::group(['prefix' => 'ratings'], function () {
    Route::post('/add', [RatingController::class, 'save']);
});

Route::group(['prefix' => 'cart'], function () {
    Route::get('', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'save']);
    Route::post('/update/{id}', [CartController::class, 'update']);
    Route::post('/delete/{id}', [CartController::class, 'destroy']);
});

Route::group(['prefix' => 'wishlist'], function () {
    Route::get('', [WishListController::class, 'index']);
    Route::post('/add', [WishListController::class, 'save']);
    Route::post('/delete/{id}', [WishListController::class, 'destroy']);
});

//states
Route::group(['prefix' => 'states'], function () {
    Route::get('', [StateController::class, 'index']);
    Route::get('/{id}', [StateController::class, 'find']);
});

//locations
Route::group(['prefix' => 'pickup-locations'], function () {
    Route::get('', [PickUpLocationController::class, 'index']);
    Route::get('/{id}', [PickUpLocationController::class, 'find']);
});

//order
Route::group(['prefix' => 'orders'], function () {
    Route::post('/make', [OrderController::class, 'save']);
    Route::get('/user', [OrderController::class, 'userOrders']);
    Route::get('/{id}', [OrderController::class, 'find']);
});

//payment
Route::group(['prefix' => 'payments'], function () {
    Route::get('', [PaymentController::class, 'index']);
    Route::get('/{id}', [PaymentController::class, 'find']);
});

//flutterwave payment
Route::group(['prefix' => 'payment'], function () {
    Route::post('/make', [FlutterwaveController::class, 'initialize']);
    Route::get('/webhook', [FlutterwaveController::class, 'webhook']);
    Route::get('/callback/flutterwave', [FlutterwaveController::class, 'callback'])->name('callback');
});

Route::group(['prefix' => 'payment'], function () {
    Route::post('/make', [StripeController::class, 'initialize']);
    Route::get('/webhook', [StripeController::class, 'webhook']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['admin']], function () {
    Route::group(['prefix' => 'categories'], function () {
        Route::get('', [AdminCategoryController::class, 'index']);
        Route::post('/add', [AdminCategoryController::class, 'save']);
        Route::post('/update/{id}', [AdminCategoryController::class, 'update']);
        Route::get('/{id}', [AdminCategoryController::class, 'find']);
        Route::post('/delete/{id}', [AdminCategoryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'tags'], function () {
        Route::get('', [AdminTagController::class, 'index']);
        Route::post('/add', [AdminTagController::class, 'save']);
        Route::post('/update/{id}', [AdminTagController::class, 'update']);
        Route::get('/{id}', [AdminTagController::class, 'find']);
        Route::post('/delete/{id}', [AdminTagController::class, 'destroy']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('', [AdminProductController::class, 'index']);
        Route::get('/dashboard', [AdminProductController::class, 'dashboard']);
        Route::post('/add', [AdminProductController::class, 'save']);
        Route::post('/update/{id}', [AdminProductController::class, 'update']);
        Route::get('/{id}', [AdminProductController::class, 'find']);
        Route::post('/delete/{id}', [AdminProductController::class, 'destroy']);
    });

    Route::group(['prefix' => 'states'], function () {
        Route::get('', [AdminStateController::class, 'index']);
        Route::post('/add', [AdminStateController::class, 'save']);
        Route::post('/update/{id}', [AdminStateController::class, 'update']);
        Route::get('/{id}', [AdminStateController::class, 'find']);
        Route::post('/delete/{id}', [AdminStateController::class, 'destroy']);
    });

    Route::group(['prefix' => 'pickup-locations'], function () {
        Route::get('', [AdminPickUpLocationController::class, 'index']);
        Route::post('/add', [AdminPickUpLocationController::class, 'save']);
        Route::post('/update/{id}', [AdminPickUpLocationController::class, 'update']);
        Route::get('/{id}', [AdminPickUpLocationController::class, 'find']);
        Route::post('/delete/{id}', [AdminPickUpLocationController::class, 'destroy']);
    });

    Route::group(['prefix' => 'weights'], function () {
        Route::get('', [AdminWeightController::class, 'index']);
        Route::post('/add', [AdminWeightController::class, 'save']);
        Route::post('/update/{id}', [AdminWeightController::class, 'update']);
        Route::get('/{id}', [AdminWeightController::class, 'find']);
        Route::post('/delete/{id}', [AdminWeightController::class, 'destroy']);
    });

    Route::group(['prefix' => 'weight-pricings'], function () {
        Route::get('', [AdminWeightPricingController::class, 'index']);
        Route::post('/add', [AdminWeightPricingController::class, 'save']);
        Route::post('/update/{id}', [AdminWeightPricingController::class, 'update']);
        Route::get('/{id}', [AdminWeightPricingController::class, 'find']);
        Route::post('/delete/{id}', [AdminWeightPricingController::class, 'destroy']);
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::get('', [AdminOrderController::class, 'index']);
        Route::post('/update-status/{id}', [AdminOrderController::class, 'updateStatus']);
        Route::get('/{id}', [AdminOrderController::class, 'find']);
    });

    Route::group(['prefix' => 'payments'], function () {
        Route::get('', [AdminPaymentController::class, 'index']);
        Route::get('/{id}', [AdminPaymentController::class, 'find']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('', [AdminUserController::class, 'index']);
        Route::get('/{id}', [AdminUserController::class, 'find']);
    });

});
