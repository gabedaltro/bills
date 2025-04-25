<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\UserController;

Route::apiResource('users', UserController::class);
Route::get('/users/{id}/bills', [UserController::class, 'bills']);

Route::apiResource('banks', BankController::class);

Route::apiResource('bills', BillController::class);
Route::get('/bills/{id}/user', [BillController::class, 'user']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
