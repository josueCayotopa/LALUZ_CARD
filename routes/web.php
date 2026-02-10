<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Luzcard\RegistroWebController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

// Rutas de Login (Se mantienen igual)
Route::get('/', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.post');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Rutas Protegidas (Dashboard y Gestión de Afiliados)
Route::middleware(['auth'])->group(function () {
    
    // Listado (Dashboard)
    Route::get('/dashboard', [RegistroWebController::class, 'index'])->name('dashboard');
    
    // Formulario de Creación
    Route::get('/afiliados/crear', [RegistroWebController::class, 'create'])->name('afiliados.create');
    
    // Guardar (POST)
    Route::post('/afiliados', [RegistroWebController::class, 'store'])->name('afiliados.store');
    Route::get('/paciente/buscar/{dni}', [PacienteController::class, 'buscarPorDni']);
});