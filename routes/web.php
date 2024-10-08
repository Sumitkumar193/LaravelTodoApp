<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [TodoController::class, 'index']);
Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
Route::put('/update/{todo}', [TodoController::class, 'update'])->name('todo.update');
Route::delete('/delete/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
