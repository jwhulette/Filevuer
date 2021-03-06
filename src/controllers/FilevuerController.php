<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Jwhulette\Filevuer\Services\SessionService;
use Jwhulette\Filevuer\Services\ConnectionServiceInterface;
use Jwhulette\Filevuer\Services\ConfigurationServiceInterface;

class FilevuerController extends Controller
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
        return view('filevuer::index', [
            'connections' => $this->configurationService->getConnectionDisplayList(),
            'loggedIn'    => session()->get(SessionService::FILEVUER_LOGGEDIN, false)  ? 'true' : 'false',
            'selected'    => session()->get(SessionService::FILEVUER_CONNECTION_NAME, ''),
            'prefix'      => \config('filevuer.prefix')
        ]);
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
        $result = ['error' => 'Unable to connect'];

        try {
            $config = $this->configurationService->getSelectedConnection($request->connection);

            $result = $this->connectionService->connectToService($config);

            if ($result) {
                session()->put(SessionService::FILEVUER_LOGGEDIN, true);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
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
            'active' => session()->get(SessionService::FILEVUER_LOGGEDIN, false)
        ]);
    }
}
