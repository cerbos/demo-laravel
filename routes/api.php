<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/expenses', [ExpenseController::class, 'list']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'get']);
    Route::post('/expenses', [ExpenseController::class, 'create']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'delete']);
    Route::patch('/expenses/{id}', [ExpenseController::class, 'update']);
    Route::post('/expenses/{id}/approve', [ExpenseController::class, 'approve']);
    Route::post('/expenses/{id}/reject', [ExpenseController::class, 'reject']);
});


