<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndicadorController;

// Página inicial
Route::get('/', [IndicadorController::class, 'index'])
    ->middleware('auth')
    ->name('indicadores.index');

// Login
Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/login', [AuthController::class, 'authenticate'])
    ->name('login.authenticate');

// Cadastro
Route::get('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/register', [AuthController::class, 'store'])
    ->name('register.store');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Rotas protegidas
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

    Route::get('/consulta', [IndicadorController::class, 'consultar'])
        ->name('indicadores.consultar');
});
