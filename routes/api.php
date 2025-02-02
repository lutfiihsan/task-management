<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

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

Route::post('register', [AuthController::class, 'register'])->name('service.register');
Route::post('login', [AuthController::class, 'login'])->name('service.login');
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('user', [AuthController::class, 'user'])->name('service.user');
    Route::post('logout', [AuthController::class, 'logout'])->name('service.logout');

    //user
    Route::get('users', [UserController::class, 'index'])->name('service.users.index');

    //task
    Route::get('tasks', [TaskController::class, 'index'])->name('service.tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('service.tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('service.tasks.show');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('service.tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('service.tasks.destroy');
    Route::post('tasks/{task}/assign', [TaskController::class, 'assign'])->name('service.tasks.assign.update');
    Route::get('tasks/{task}/history', [TaskController::class, 'history'])->name('service.tasks.history.show');
    Route::get('tasks/get/notification', [TaskController::class, 'notification'])->name('service.tasks.notification.show');
});
