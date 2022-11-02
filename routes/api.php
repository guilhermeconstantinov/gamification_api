<?php

use App\Http\Controllers\BackOfficeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function() {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/checkin', [UserController::class, 'checkin']);
    Route::post('/simulation', [UserController::class, 'simulation']);
    Route::post('/generate-number', [UserController::class, 'generateNumber']);
    Route::post('/validation-code', [UserController::class, 'validationCode']);
    Route::post('/read-validation-code', [UserController::class, 'readValidation']);
});

Route::post('/register', [UserController::class, 'create']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('app')->group(function() {
    Route::post('/qrcode-read', [BackOfficeController::class, 'qrcodeRead']);
    Route::post('/new-raffle', [BackOfficeController::class, 'wonPrize']);
    Route::get('/raffles', [BackOfficeController::class, 'rafflesList']);
    Route::get('/lucky-number-count', [BackOfficeController::class, 'luckyNumberCount']);
    Route::get('/consult-users',  [BackOfficeController::class, 'consultUsers']);
});
Route::get('/raffle-numbers', [BackOfficeController::class, 'getNumbersByRaffleId']);
Route::get('/images/{filename}', [UserController::class, 'images']);
