<?php

use App\Http\Controllers\AccessCardController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('test', [TestController::class, 'index']);
Route::any('insert_data', [AccessCardController::class, 'insertLiveData']);
Route::get('fetch_last_data', [AccessCardController::class, 'fetchLastRecord']);
Route::get('insert_record', [AccessCardController::class, 'insert_record']);
