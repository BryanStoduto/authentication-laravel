<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndicadorController;

// Tela 2 (após o login): agora mostra os indicadores no lugar da welcome.
Route::get('/', [IndicadorController::class, 'index'])
    ->middleware('auth')
    ->name('indicadores.index');

Route::middleware('auth')->group(function () {
    Route::post('/indicadores', [IndicadorController::class, 'store'])->name('indicadores.store');
    Route::put('/indicadores/{indicador}', [IndicadorController::class, 'update'])->name('indicadores.update');
    Route::delete('/indicadores/{indicador}', [IndicadorController::class, 'destroy'])->name('indicadores.destroy');
    Route::post('/indicadores/{indicador}/sincronizar', [IndicadorController::class, 'sincronizar'])->name('indicadores.sincronizar');
});

// Alterado de 'showLoginForm' para 'login'
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');