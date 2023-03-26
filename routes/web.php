<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index']);

Route::get('product', [ProductController::class, 'seeAll']);
Route::get('product/category/{categoryId}', [ProductController::class, 'seeAllCategory'])->where(['categoryId' => '[0-9]+']);
Route::get('product/{id}', [ProductController::class, 'show'])->where(['id' => '[0-9]+']);

Route::middleware('auth', 'admin')->group(function () {
    Route::post('category', [CategoryController::class, 'create']);
    Route::put('category/{id}', [CategoryController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::delete('category/{id}', [CategoryController::class, 'delete'])->where(['id' => '[0-9]+']);

    Route::post('/product', [ProductController::class, 'create']);
    Route::put('/product/{id}', [ProductController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::delete('/product/{id}', [ProductController::class, 'delete'])->where(['id' => '[0-9]+']);

    Route::get("/user/control", [UserController::class, 'see']);
    Route::put("/user/{id}", [UserController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::delete("/user/{id}", [UserController::class, 'remove'])->where(['id' => '[0-9]+']);
    Route::put('/user/{id}/reset_password', [UserController::class, 'resetPassword'])->where(['id' => '[0-9]+']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/shopping', [ShoppingController::class, 'seeAll']);
    Route::post('/shopping', [ShoppingController::class, 'add']);
    Route::post('/shopping/buy', [ShoppingController::class, 'addAndBuy']);
    Route::put('/shopping/{id}', [ShoppingController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::delete('/shopping/{id}', [ShoppingController::class, 'remove'])->where(['id' => '[0-9]+']);

    Route::post('/buy', [ShoppingController::class, 'buyAll']);
    Route::get('/buy', [ShoppingController::class, 'dataBuy']);

    Route::post('/bank', [BankController::class, 'create']);
    Route::put('/bank/{id}', [BankController::class, 'update'])->where(['id' => '[0-9]+']);
    Route::delete('/bank/{id}', [BankController::class, 'remove'])->where(['id' => '[0-9]+']);

    Route::post('/product/{id}/opinion', [ProductController::class, 'createOpinion'])->where(['id' => '[0-9]+']);
    Route::delete('/product/opinion/{id}', [ProductController::class, 'deleteOpinion'])->where(['id' => '[0-9]+']);
});

require __DIR__.'/auth.php';
