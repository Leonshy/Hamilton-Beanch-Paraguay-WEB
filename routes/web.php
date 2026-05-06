<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('index'));
Route::get('/productos', fn() => view('productos'));
Route::get('/productos/{id}', fn($id) => view('producto-detalle'));
Route::get('/preguntas-frecuentes', fn() => view('preguntas-frecuentes'));
Route::get('/centro-ayuda', fn() => view('centro-ayuda'));
Route::get('/servicio-tecnico', fn() => view('servicio-tecnico'));
Route::get('/manuales-de-producto', fn() => view('manuales-de-producto'));
Route::get('/garantia-de-producto', fn() => view('garantia-de-producto'));
Route::get('/contacto', fn() => view('contacto'));
