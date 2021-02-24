<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Exception;
use Jwhulette\Filevuer\Services\SessionService;

class ConnectionService implements ConnectionServiceInterface
{
    /**
     * @param string|null $connection
     *
     * @return bool
     */
    public function connectToService(?string $connection = null): bool
    {
        if (\is_null($connection)) {
            throw new Exception('Unkown filesystem disk');
        }

        SessionService::setConnectionName($connection);

        SessionService::setLoggedInTrue();

        return true;
    }

    /**
     * Remove session values
     */
    public function logout(): void
    {
        SessionService::forget();
    }
}
