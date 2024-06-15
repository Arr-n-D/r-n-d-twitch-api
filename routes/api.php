<?php

use App\Http\Controllers\MembersController;
use App\Http\Middleware\EnsureAPIToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('members', MembersController::class)->middleware(EnsureAPIToken::class);