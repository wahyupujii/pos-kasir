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

Route::group(['middleware' => 'guest'], function () {

    Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
    Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
});


Route::middleware(['auth'])->group(function () {
    // All routes that require authentication
    Route::get('/', App\Livewire\Pos::class)->name('pos');
    Route::get('product', App\Livewire\ProductList::class)->name('product');
    Route::get('product/create', App\Livewire\ProductCreate::class);
    Route::get('product/edit/{id}', App\Livewire\ProductEdit::class)->name('posts.edit');
    Route::get('order', App\Livewire\OrderList::class)->name('order');
});
