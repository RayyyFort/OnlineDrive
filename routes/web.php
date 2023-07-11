<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('About',['logged' => Auth::check()]);
})->name('About');;

Route::get('/Files', [App\Http\Controllers\FilesController::class, 'Index'])->name('Files');

Route::post('/File/FileChanged', [App\Http\Controllers\FilesController::class, 'FileChanged'])->name('fileChanged');

Route::get('/Files/{PathToGo}', [App\Http\Controllers\FilesController::class, 'Index'])->name('Files.CustomPath');

Route::get('/DeleteTemp', [App\Http\Controllers\FilesController::class, 'DeleteTemp'])->name('Files.DeleteTemp');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/Heartbeat', [App\Http\Controllers\FilesController::class, 'Heartbeat'])->name('Heartbeat');

require __DIR__.'/auth.php';