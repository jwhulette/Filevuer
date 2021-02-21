<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// use Jwhulette\Filevuer\Controllers\FileController;
// use Jwhulette\Filevuer\Controllers\DownloadController;
// use Jwhulette\Filevuer\Controllers\FilevuerController;
// use Jwhulette\Filevuer\Controllers\DirectoryController;

// Route::middleware(['web', 'sessionDriver'])->prefix('filevuer')->group(function () {
//     Route::get('/', [FilevuerController::class, 'index'])->name('filevuer.index');

//     Route::get('/poll', [FilevuerController::class, 'poll'])->name('filevuer.poll');

//     Route::get('/logout', [FilevuerController::class, 'logout'])->name('filevuer.logout');

//     Route::post('/', [FilevuerController::class, 'connect'])->name('filevuer.hash');

//     Route::post('/download', [DownloadController::class, 'generate'])->name('filevuer.generate');

//     Route::get('/download/{hash}', [DownloadController::class, 'download'])->name('filevuer.download');

//     Route::post('/upload', [DownloadController::class, 'upload'])->name('filevuer.upload');

//     Route::group([
//         'prefix' => 'file',
//         'as' => 'filevuer.file'
//     ], function () {
//         Route::get('/', [FileController::class, 'show']);

//         Route::post('/', [FileController::class, 'create']);

//         Route::put('/', [FileController::class, 'update']);

//         Route::delete('/', [FileController::class, 'destroy']);
//     });

//     Route::group([
//         'prefix' => 'directory',
//         'as' => 'filevuer.directory'
//     ], function () {
//         Route::get('/', [DirectoryController::class, 'index']);

//         Route::post('/', [DirectoryController::class, 'create']);

//         Route::delete('/', [DirectoryController::class, 'destroy']);
//     });
// });

$namespace = 'jwhulette\filevuer\controllers\\';

Route::group([
    'prefix' => 'filevuer',
    'middleware' => [
        'web',
        'sessionDriver'
    ],
], function () use ($namespace) {
    Route::get('/', $namespace . 'FilevuerController@index')->name('filevuer.index');

    Route::get('/poll', $namespace . 'FilevuerController@poll')->name('filevuer.poll');

    Route::get('/logout', $namespace . 'FilevuerController@logout')->name('filevuer.logout');

    Route::post('/', $namespace . 'FilevuerController@connect')->name('filevuer.hash');

    Route::post('/download', $namespace . 'DownloadController@generate')->name('filevuer.generate');

    Route::get('/download/{hash}', $namespace . 'DownloadController@download')->name('filevuer.download');

    Route::post('/upload', $namespace . 'UploadController@upload')->name('filevuer.upload');

    Route::group([
        'prefix' => 'file',
        'as' => 'filevuer.file'
    ], function () use ($namespace) {
        Route::get('/', $namespace . 'FileController@show');

        Route::post('/', $namespace . 'FileController@create');

        Route::put('/', $namespace . 'FileController@update');

        Route::delete('/', $namespace . 'FileController@destroy');
    });

    Route::group([
        'prefix' => 'directory',
        'as' => 'filevuer.directory'
    ], function () use ($namespace) {
        Route::get('/', $namespace . 'DirectoryController@index');

        Route::post('/', $namespace . 'DirectoryController@create');

        Route::delete('/', $namespace . 'DirectoryController@destroy');
    });
});
