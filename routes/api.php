<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {
    Route::apiResource('employees', \App\Http\Controllers\Api\V1\EmployeeController::class);
    Route::apiResource('tickets', \App\Http\Controllers\Api\V1\TicketController::class);
    Route::prefix('/reports')->group(function () {
        Route::get('tickets/by/employee/period', [\App\Http\Controllers\Api\V1\ReportController::class, 'searchTicketsByEmployeeAndPeriod']);
        Route::post('tickets/generate', [\App\Http\Controllers\Api\V1\ReportController::class, 'generateReportSearchTickets']);
    });
});

