<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/tugas', function () {
    return view('tasks.index');
})->name('tugas.index');

Route::get('/tugas/create', function () {
    return view('tasks.create');
})->name('tugas.create');

Route::get('/tugas/{task}/edit', function () {
    return view('tasks.edit');
})->name('tugas.edit');
