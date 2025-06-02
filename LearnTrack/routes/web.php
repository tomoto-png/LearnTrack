<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TimerController;
use App\Http\Controllers\StudyDataController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AnswerReplyController;

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
    Route::prefix('timer')->group(function () {
        //集中タイマー管理
        Route::get('/', [TimerController::class, 'index'])->name('timer.index');
        Route::post('/start/{plan?}', [TimerController::class, 'start'])->name('timer.start');
        Route::put('/stop/{studySession}', [TimerController::class, 'stop'])->name('timer.stop');
        Route::get('/settings', [TimerController::class, 'timerSettings'])->name('timer.settings');
        Route::post('/saveTimerSettings', [TimerController::class, 'saveTimerSettings'])->name('pomodoro.saveTimerSettings');
        //ポモドーロタイマー管理
        Route::get('/pomodoro', [TimerController::class, 'pomodoroIndex'])->name('pomodoro.index');
        Route::post('/pomodoro/start/{plan?}', [TimerController::class, 'pomodoroStart'])->name('pomodoro.start');
        Route::put('/pomodoro/stop/{studySession}', [TimerController::class, 'pomodoroStop'])->name('pomodoro.stop');
        Route::get('/pomodoro/settings', [TimerController::class, 'pomodoroSettings'])->name('pomodoro.settings');
        Route::post('/pomodoro/savePomodoroSettings', [TimerController::class, 'savePomodoroSettings'])->name('pomodoro.savePomodoroSettings');
    });
    Route::prefix('studyData')->group(function () {
        Route::get('/', [StudyDataController::class, 'index'])->name('studyData.index');
        Route::post('/calendar/click', [StudyDataController::class, 'fetchData']);
    });
    Route::prefix('question')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('question.index');
        Route::get('/create', [QuestionController::class, 'create'])->name('question.create');
        Route::get('/cancel', [QuestionController::class, 'cancel'])->name('question.cancel');
        Route::post('/', [QuestionController::class, 'store'])->name('question.store');
        Route::get('/show/{id}', [QuestionController::class, 'show'])->name('question.show');
        Route::get('/{id}/answer', [AnswerController::class, 'create'])->name('answer.create');
        Route::post('/answers/store', [AnswerController::class, 'store'])->name('answer.store');
        Route::get('/cancel/{id}', [AnswerController::class, 'cancel'])->name('answer.cancel');
        Route::get('/{id}/replie', [AnswerReplyController::class, 'create']) ->name('replie.create');
        Route::post('/replie/store', [AnswerReplyController::class, 'store'])->name('replie.store');
    });
});
