<?php

<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\EmployeeController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::resource('employee', EmployeeController::class)->only([
        'index', 'show', 'store', 'destroy'
    ]);
});
=======
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\EmployeeController;

Route::post('/employee', [EmployeeController::class, 'store']);
Route::get('/employee', [EmployeeController::class, 'index']);
Route::get('/employee/{id}', [EmployeeController::class, 'show']);
Route::delete('/employee/{id}', [EmployeeController::class, 'destroy']);
>>>>>>> main
