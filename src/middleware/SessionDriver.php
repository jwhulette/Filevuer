<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jwhulette\Filevuer\Services\SessionService;

class SessionDriver
{
    /**
     * Apply the login data from the session storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $loggedIn = session()->get(SessionService::FILEVUER_LOGGEDIN, false)  ? 'true' : 'false';

        // if ('true' === $loggedIn) {
        //     $this->applyConfiguration();
        // }

        return $next($request);
    }
}
