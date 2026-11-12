<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndicadorController;

Route::get('/', [IndicadorController::class, 'index'])
    ->middleware('auth')
    ->name('indicadores.index');

Route::middleware('auth')->group(function () {

    Route::get('/sincronizacao/erro', function () {
        return view('erro-sincronizacao');
    })->name('sincronizacao.erro');

    Route::post('/indicadores', [IndicadorController::class, 'store'])
        ->name('indicadores.store');

    Route::put('/indicadores/{indicador}', [IndicadorController::class, 'update'])
        ->name('indicadores.update');

    Route::delete('/indicadores/{indicador}', [IndicadorController::class, 'destroy'])
        ->name('indicadores.destroy');

    Route::post('/indicadores/{indicador}/sincronizar', [IndicadorController::class, 'sincronizar'])
        ->name('indicadores.sincronizar');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
