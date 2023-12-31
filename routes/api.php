<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthenticationController;

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

Route::post('/login', [AuthenticationController::class, 'login']);

Route::post('/register', [AuthenticationController::class, 'register']);

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', [AuthenticationController::class, 'index']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books/add', [BookController::class, 'store']);
    Route::put('/books/{book_id}/edit', [BookController::class, 'update'])->middleware('book-user');
    Route::delete('/books/{book_id}/delete', [BookController::class, 'destroy'])->middleware('book-user');
    Route::get('/books/{book_id}', [BookController::class, 'detail']);
});