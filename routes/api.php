<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\EmployeeController;

Route::post('/employee', [EmployeeController::class, 'store']);
Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/{id}', [EmployeeController::class, 'show']);
Route::delete('/employee/{id}', [EmployeeController::class, 'destroy']);
