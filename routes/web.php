<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/single/{post}', [SingleController::class, 'index'])->name('single');
Route::post('/single/{post}/comment', [SingleController::class, 'comment'])
    ->middleware('auth:web')->name('single.comment');
// for me ===============================================================================================
Route::get('/admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
// for login ===============================================================================================
Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
