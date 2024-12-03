<?php

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('index');
// });

Route::prefix("auth")->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
});

Route::middleware('auth')->prefix("/")->group(function () {
    Route::get('/', [ChatController::class, 'index']);

    Route::post('/', function (Request $request) {
        event(new ChatEvent($request->message));
        return response()->json(['status' => 'success']);
    });
});
