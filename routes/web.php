<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Jwhulette\Filevuer\Controllers\FileController;
use Jwhulette\Filevuer\Controllers\UploadController;
use Jwhulette\Filevuer\Controllers\DownloadController;
use Jwhulette\Filevuer\Controllers\FilevuerController;
use Jwhulette\Filevuer\Controllers\DirectoryController;

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

Route::middleware(['web', 'sessionDriver'])
    ->prefix('filevuer')
    ->group(function () {
        Route::get('/', [FilevuerController::class, 'index'])->name('filevuer.index');

        Route::get('/poll', [FilevuerController::class, 'poll'])->name('filevuer.poll');

        Route::get('/logout', [FilevuerController::class, 'logout'])->name('filevuer.logout');

        Route::post('/', [FilevuerController::class, 'connect'])->name('filevuer.hash');

        Route::post('/download', [DownloadController::class, 'generate'])->name('filevuer.generate');

        Route::get('/download/{hash}', [DownloadController::class, 'download'])->name('filevuer.download');

        Route::post('/upload', [UploadController::class, 'upload'])->name('filevuer.upload');

        Route::prefix('file')
            ->name('filevuer.file')
            ->group(function () {
                Route::get('/', [FileController::class, 'show']);

                Route::post('/', [FileController::class, 'create']);

                Route::put('/', [FileController::class, 'update']);

                Route::delete('/', [FileController::class, 'destroy']);
            });

        Route::prefix('directory')
            ->name('filevuer.directory.')
            ->group(function () {
                Route::get('/', [DirectoryController::class, 'index'])->name('index');

                Route::post('/', [DirectoryController::class, 'create'])->name('create');

                Route::delete('/', [DirectoryController::class, 'destroy'])->name('destroy');
            });
    });
