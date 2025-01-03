<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;

// 認証関連
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    //マイページ管理
    Route::prefix("profile")->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
    });
    //学習計画管理
    Route::prefix("plan")->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('plan.index');
        Route::get('/create', [PlanController::class, 'create'])->name('plan.create');
        Route::post('/', [PlanController::class, 'store'])->name('plan.store');
        Route::get('{id}/edit', [PlanController::class, 'edit'])->name('plan.edit');
        Route::put('{id}', [PlanController::class, 'update'])->name('plan.update');
        Route::delete('{id}', [PlanController::class, 'destroy'])->name('plan.destroy');
    });
});
