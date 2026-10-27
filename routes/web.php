<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\ModeracaoController;
use App\Http\Controllers\PerguntaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventoController::class, 'index'])->name('eventos.index');
Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create')->middleware('auth');
Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store')->middleware('auth');
Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
Route::post('/eventos/{id}/perguntas', [EventoController::class, 'storePergunta'])->name('eventos.perguntas.store')->middleware('auth');
Route::delete('/perguntas/{pergunta}', [EventoController::class, 'destroyPergunta'])->name('perguntas.destroy')->middleware('auth');
Route::post('/perguntas/{pergunta}/votar', [PerguntaController::class, 'votar'])->name('perguntas.votar')->middleware('auth');
Route::post('/perguntas/{pergunta}/moderar', [ModeracaoController::class, 'store'])->name('moderacoes.store')->middleware('auth');
Route::get('/eventos/{evento}/moderacoes', [ModeracaoController::class, 'index'])->name('moderacoes.index')->middleware('auth');
Route::patch('/moderacoes/{moderacao}/status', [ModeracaoController::class, 'updateStatus'])->name('moderacoes.status')->middleware('auth');
