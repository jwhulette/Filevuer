<?php

namespace Jwhulette\Filevuer\controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use jwhulette\filevuer\services\SessionInterface;
use jwhulette\filevuer\services\ConnectionServiceInterface;
use jwhulette\filevuer\services\ConfigurationServiceInterface;

class FilevuerController extends Controller implements SessionInterface
{
    /**
     * @var ConfigurationServiceInterface
     */
    protected $configurationService;

    /**
     * @var ConnectionServiceInterface
     */
    protected $connectionService;

    /**
     * __construct
     *
     * @param ConfigurationServiceInterface $configurationService
     * @param ConnectionServiceInterface $connectionService
     */
    public function __construct(ConfigurationServiceInterface $configurationService, ConnectionServiceInterface $connectionService)
    {
        $this->configurationService = $configurationService;
        $this->connectionService    = $connectionService;
    }

    /**
     * index
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->development();
        $config = $this->configurationService->getConnectionDisplayList();
        $loggedIn = session()->get(SessionInterface::FILEVUER_LOGGEDIN, false)  ? 'true' : 'false';

        return view('filevuer::index', [
            'connections' => $config->toJson(),
            'loggedIn'    => $loggedIn,
            'selected'    => session()->get(SessionInterface::FILEVUER_CONNECTION_NAME, '')
        ]);
    }

    private function development()
    {
        if (\App::environment('local')) {
            // Used for development
            \Artisan::call('vendor:publish', [
            '--tag' => 'filevuer',
            '--force' => 1
        ]);
        }
    }

    /**
     * Set the remote directory connection
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function connect(Request $request)
    {
        $config = $this->configurationService->getSelectedConnection($request->connection);
        $result = $this->connectionService->connectToService($config);
        if ($result) {
            session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);
        }
        
        return response()->json($result);
    }

    /**
     * Undocumented function
     *
     * @return Redirect
     */
    public function logout()
    {
        $this->connectionService->logout();
        return redirect()->route('filevuer.index');
    }

    public static function routes()
    {
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
    }
}
