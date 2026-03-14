<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Product pages
Route::get('/products/sharepoint', fn() => view('products.sharepoint'))->name('products.sharepoint');
Route::get('/products/teams', fn() => view('products.teams'))->name('products.teams');
Route::get('/products/onenote', fn() => view('products.onenote'))->name('products.onenote');
Route::get('/products/onedrive', fn() => view('products.onedrive'))->name('products.onedrive');
Route::redirect('/products/semantic-search', '/products/pipeline', 301);
Route::get('/products/pipeline', fn() => view('products.pipeline'))->name('products.pipeline');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
