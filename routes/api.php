<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('employees', \App\Http\Controllers\Api\V1\EmployeeController::class);
Route::apiResource('tickets', \App\Http\Controllers\Api\V1\TicketController::class);
