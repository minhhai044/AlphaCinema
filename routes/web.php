<?php

use App\Http\Controllers\Admin\MovieController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/admin/movies', MovieController::class)->names([
    'index' => 'admin.movies.index',
    'create' => 'admin.movies.create',
    'store' => 'admin.movies.store',
    'show' => 'admin.movies.show',
    'edit' => 'admin.movies.edit',
    'update' => 'admin.movies.update',
    'destroy' => 'admin.movies.destroy',
]);