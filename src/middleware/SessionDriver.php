<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Middleware;

use Closure;
use Jwhulette\Filevuer\Services\SessionInterface;
use Jwhulette\Filevuer\Traits\SessionDriverTrait;

class SessionDriver
{
    use SessionDriverTrait;

    /**
     * Apply the login data from the session storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $loggedIn = session()->get(SessionInterface::FILEVUER_LOGGEDIN, false)  ? 'true' : 'false';

        if ('true' === $loggedIn) {
            $this->applyConfiguration();
        }

        return $next($request);
    }
}
