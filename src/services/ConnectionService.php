<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionInterface;

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

        session()->put(SessionInterface::FILEVUER_CONNECTION_NAME, $connection);

        session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);

        return true;
    }

    /**
     * Remove session values
     */
    public function logout(): void
    {
        session()->forget([
            SessionInterface::FILEVUER_LOGGEDIN,
            SessionInterface::FILEVUER_CONNECTION_NAME,
        ]);
    }
}
