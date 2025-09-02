<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*Route::middleware('auth')->group(function () {*/
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/{product}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    /* Route::get('/product/{product}/choice', [ProductController::class, 'choice'])->name('product.choice');*/


    
    
    
    
    Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/create', function() {
    return view('auth.register');
})->name('create');

    Route::get('/register2', function () {
    return view('auth.register2');
})->name('register2');

require __DIR__.'/auth.php';
