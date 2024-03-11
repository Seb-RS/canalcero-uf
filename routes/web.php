<?php

use App\Http\Controllers\UnidadFomentoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [UnidadFomentoController::class, "index"])->name('home');

Route::get('/getUF/{year}', [UnidadFomentoController::class, "getUfFromYear"])->name('home');
