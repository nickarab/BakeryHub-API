<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get("/status", function(){
    return response()->json(
        [
        'status' => 'ok',
        'message' => 'API is running',
    ], 200);
});

Route::apiResource('user', UserController::class);
