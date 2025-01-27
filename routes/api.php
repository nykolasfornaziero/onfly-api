<?php

use App\Http\Controllers\API\TravelDestinationController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::get('user', [AuthController::class, 'getUser'])->middleware('auth:api');
Route::get('teste', [TravelDestinationController::class, 'test']);


Route::prefix('destination')->group(function () {
    Route::post('/store', [TravelDestinationController::class, 'store'])->middleware(['auth:api', 'role_permission:admin,colaborator'])->name('travel-destinations.store');;
    Route::get('/list', [TravelDestinationController::class, 'index'])->middleware(['auth:api', 'role_permission:admin,colaborator']);;
    Route::get('/checkStatus/{id}', [TravelDestinationController::class, 'checkStatus'])->middleware(['auth:api', 'role_permission:admin,colaborator']);;
    Route::patch('/updateStatus/{id}', [TravelDestinationController::class, 'updateStatus'])->middleware(['auth:api', 'role_permission:admin']);
    Route::patch('/update/{id}', [TravelDestinationController::class, 'update'])->middleware('auth:api')->middleware(['auth:api', 'role_permission:admin,colaborator']);
});
