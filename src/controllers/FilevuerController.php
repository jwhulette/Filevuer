<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Jwhulette\Filevuer\Services\SessionInterface;
use Jwhulette\Filevuer\Services\ConnectionServiceInterface;
use Jwhulette\Filevuer\Services\ConfigurationServiceInterface;

class FilevuerController extends Controller implements SessionInterface
{
    protected ConfigurationServiceInterface $configurationService;
    protected ConnectionServiceInterface $connectionService;

    /**
     * @param ConfigurationServiceInterface $configurationService
     * @param ConnectionServiceInterface $connectionService
     */
    public function __construct(
        ConfigurationServiceInterface $configurationService,
        ConnectionServiceInterface $connectionService
    ) {
        $this->configurationService = $configurationService;

        $this->connectionService    = $connectionService;
    }

    /**
     * index
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $this->development();

        return view('filevuer::index', [
            'connections' => $this->configurationService->getConnectionDisplayList()->toJson(),
            'loggedIn'    => session()->get(SessionInterface::FILEVUER_LOGGEDIN, false)  ? 'true' : 'false',
            'selected'    => session()->get(SessionInterface::FILEVUER_CONNECTION_NAME, '')
        ]);
    }

    /**
     * When developing the applicaton
     * copy the updated files over on page refresh
     *
     * @return void
     */
    private function development()
    {
        if (\App::environment('local')) {
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
    public function connect(Request $request): JsonResponse
    {
        $config = $this->configurationService->getSelectedConnection($request->connection);

        $result = $this->connectionService->connectToService($config);

        if ($result) {
            session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);
        }

        return response()->json($result);
    }

    /**
     * Logout the user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->connectionService->logout();

        return redirect()->route('filevuer.index');
    }

    /**
     * Check to see if the user is logged in
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function poll(): JsonResponse
    {
        return response()->json([
            'active' => session()->get(SessionInterface::FILEVUER_LOGGEDIN, false)
        ]);
    }
}
