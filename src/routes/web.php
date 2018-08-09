<?php

Route::group(['prefix' => 'filevuer', 'middleware' => ['web','sessionDriver']], function () {
    Route::get('/', 'jwhulette\filevuer\controllers\FilevuerController@index')->name('filevuer.index');
    Route::get('/logout', 'jwhulette\filevuer\controllers\FilevuerController@logout')->name('filevuer.logout');
    Route::post('/', 'jwhulette\filevuer\controllers\FilevuerController@connect')->name('filevuer.hash');

    Route::post('/download', 'jwhulette\filevuer\controllers\DownloadController@generate')->name('filevuer.generate');
    Route::get('/download/{hash}', 'jwhulette\filevuer\controllers\DownloadController@download')->name('filevuer.download');
    Route::post('/upload', 'jwhulette\filevuer\controllers\UploadController@upload')->name('filevuer.upload');

    Route::group(['prefix' => 'file', 'as' => 'filevuer.file'], function () {
        Route::get('/', 'jwhulette\filevuer\controllers\FileController@show');
        Route::post('/', 'jwhulette\filevuer\controllers\FileController@create');
        Route::put('/', 'jwhulette\filevuer\controllers\FileController@update');
        Route::delete('/', 'jwhulette\filevuer\controllers\FileController@destroy');
    });

    Route::group(['prefix' => 'directory', 'as' => 'filevuer.directory'], function () {
        Route::get('/', 'jwhulette\filevuer\controllers\DirectoryController@index');
        Route::post('/', 'jwhulette\filevuer\controllers\DirectoryController@create');
        Route::delete('/', 'jwhulette\filevuer\controllers\DirectoryController@destroy');
    });
});
