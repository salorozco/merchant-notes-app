<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('merchants')->controller(MerchantController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/by-user/{user}', [MerchantController::class, 'merchantsByUser']);
    Route::get('/{merchant}', [MerchantController::class, 'show']);
});

Route::prefix('merchants/{merchant}/notes')->controller(NoteController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{note}', 'update');
        Route::delete('/{note}', 'destroy');
});

Route::get('/users/{user}/notes', [NoteController::class, 'notesByUser']);
