<?php

use App\Http\Controllers\PerjanjianKinerjaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'hello']);
Route::get('login', [UserController::class, 'hello'])->name('login');

Route::get('perjanjian-kinerja/export', [PerjanjianKinerjaController::class, 'export']);
Route::get('perjanjian-kinerja/export-pdf', [PerjanjianKinerjaController::class, 'exportPdf']);

Route::get('/test-db', function () {

    $model = new \App\Models\Simpeg\VStrukturOrganisasi();

    return [
        'connection_name' => $model->getConnectionName(),
        'database_name' => $model->getConnection()->getDatabaseName(),
    ];
});
