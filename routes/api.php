<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



//login and register
Route::post('/register_store', [AuthController::class, 'register_store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register_customer', [AuthController::class, 'register_customer']);
//Route::get('/products_w_images_trial_reactnative', [ProductController::class, 'getAllProductsImages']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/stores', [UserController::class, 'getStores']);
//Role Based Restful api
Route::middleware(['auth:api', 'role'])->group(function() {
//category
   // Route::middleware(['scope:store,cashier,customer'])->get('/categories', [CategoryController::class, 'index']);
    Route::middleware(['scope:store,cashier,customer'])->post('/category', [CategoryController::class, 'store']);
    Route::middleware(['scope:store,cashier,customer'])->delete('/category/{id}', [CategoryController::class, 'destroy']);
    Route::middleware(['scope:store,cashier,customer'])->get('/products_category/{id}', [CategoryController::class, 'getProducts_ByCategory']);



//gallery
    Route::middleware(['scope:store,cashier,customer'])->delete('/gallery/{id}', [GalleryController::class, 'destroy']);
    Route::middleware(['scope:store,cashier,customer'])->post('/insertimage', [GalleryController::class, 'store']);
    Route::middleware(['scope:store,cashier,customer'])->get('/getimage/{id}', [GalleryController::class, 'getImagebyId']);


//cashiers,users,customers
    Route::middleware(['scope:store,cashier,customer'])->post('/addcashier', [UserController::class, 'store_cashier']);
    Route::middleware(['scope:store,cashier,customer'])->get('/users', [UserController::class, 'index']);
    Route::middleware(['scope:store,cashier,customer'])->get('/allmembers', [UserController::class, 'getAllMembers']);
    Route::middleware(['scope:store,cashier,customer'])->delete('/user/{id}', [UserController::class, 'destroy']);
    Route::middleware(['scope:store'])->get('/customers', [UserController::class, 'getAllCustomers']);
    Route::middleware(['scope:store'])->get('/cashiers', [UserController::class, 'getAllCashiers']);
    Route::middleware(['scope:store,cashier,customer'])->post('/update_image', [UserController::class, 'update_image']);




//prodcuts
Route::middleware(['scope:store,cashier,customer'])->get('/productbyname_customer/{name}', [ProductController::class, 'getproductbyname_customer']);
Route::middleware(['scope:store,cashier,customer'])->get('/products', [ProductController::class, 'index']);
Route::middleware(['scope:store,cashier,customer'])->post('/product', [ProductController::class, 'store']);
Route::middleware(['scope:store,cashier,customer'])->get('/products_w_images', [ProductController::class, 'getAllProductsImages']);
Route::middleware(['scope:store,cashier,customer'])->delete('/product/{id}', [ProductController::class, 'destroy']);
Route::middleware(['scope:store,cashier,customer'])->get('/products_w_images_customer', [ProductController::class, 'getAllProductsImages_Customer']);
Route::middleware(['scope:store,cashier,customer'])->post('/product_edit', [ProductController::class, 'update']);
Route::middleware(['scope:store,cashier,customer'])->get('/products_w_images_all', [ProductController::class, 'index']);



//bills
Route::middleware(['scope:store,cashier,customer'])->post('/bill', [BillController::class, 'store']);
Route::middleware(['scope:store,cashier,customer'])->get('/bills_notdone', [BillController::class, 'index']);
Route::middleware(['scope:store,cashier,customer'])->put('/setbilldone', [BillController::class, 'update']);


//online orders
Route::middleware(['scope:store,cashier,customer'])->post('/order', [OrderController::class, 'store']);
Route::middleware(['scope:store,cashier,customer'])->post('/setcashier_order', [OrderController::class, 'update']);
Route::middleware(['scope:store,cashier,customer'])->get('/orders_cashier', [OrderController::class, 'getall_orders_cashier']);

//online-orders -users , push notifications;
Route::middleware(['scope:store,cashier,customer'])->post('/update_expotoken', [UserController::class, 'update_expotoken']);


});









