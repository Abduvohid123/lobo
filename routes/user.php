<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\User\RegistrationController;
use \App\Http\Controllers\Admin\CustomerPostsController;
use \App\Http\Controllers\Admin\DeclarantsController;
Route::prefix('/{lang}')->group(function () {

    Route::get('/', function () {return view('user.home.index');})->name('home');
    Route::get('/test', function () {return view('test');})->name('test');
    Route::get('/input-function',[RegistrationController::class,'carrierCarInputs'])->name('input-function');
    Route::get('/profile', function () {return view('user.profile.index', ['user'=>\Illuminate\Support\Facades\Auth::user()]);})->name('profile');
    Route::get('/truckers',[\App\Http\Controllers\Admin\CarrierPostsController::class,'index'])->name('truckers');
    Route::get('/declarants',[DeclarantsController::class,'index'])->name('declarants');
    Route::get('/customers',[CustomerPostsController::class,'index'])->name('customers');
    Route::get('/truckers-active',function (){return view('user.truckers.truckers-active.index');})->name('truckers-active');
    Route::get('/declarants-active',function (){return view('user.declarants-active.index');})->name('declarants-active');
    Route::get('/register',function (){return view('user.register.sign-up.index');})->name('register');
    Route::post('/register',[RegistrationController::class,'register'])->name('register');

});
