<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FirebaseAuthController;


use App\Http\Controllers\JasperController;

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
    return view('welcome');
});


Route::get('/test', [FirebaseAuthController::class, 'test_firebase']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/store_fcm', [FirebaseAuthController::class, 'store_fcm']);



});
Route::get('jasper/report/{name}/{ext?}', [JasperController::class, 'report']);

require __DIR__.'/auth.php';
