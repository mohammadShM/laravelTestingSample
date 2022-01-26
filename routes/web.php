<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SingleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/single/{post}', [SingleController::class, 'index'])->name('single');
Route::post('/single/{post}/comment', [SingleController::class, 'comment'])
    ->middleware('auth:web')->name('single.comment');
Route::prefix('admin')->middleware(['auth:web','admin'])->group(static function () {
    Route::resource('post', PostController::class)->except(['show']);
});
// for me ===============================================================================================
Route::get('/admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
// for login ===============================================================================================
Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
