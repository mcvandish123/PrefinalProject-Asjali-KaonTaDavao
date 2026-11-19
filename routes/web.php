<?php

use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EateryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/eateries')->name('home');

Route::resource('eateries', EateryController::class)->parameters(['eateries' => 'eatery']);
Route::post('eateries/{eatery}/reviews', [ReviewController::class, 'store'])->name('eateries.reviews.store');

Route::get('districts', [DistrictController::class, 'index'])->name('districts.index');
Route::get('districts/{district}', [DistrictController::class, 'show'])->name('districts.show');

Route::get('reviewers/{user}', [UserController::class, 'show'])->name('reviewers.show');
