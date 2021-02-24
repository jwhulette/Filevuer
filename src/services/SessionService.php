<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

class SessionService
{
    public const FILEVUER_CONNECTION_NAME = 'FILEVUER_CONNECTION_NAME';
    public const FILEVUER_LOGGEDIN        = 'FILEVUER_LOGGEDIN';
    public const FILEVUER_DOWNLOAD        = 'FILEVUER_DOWNLOAD';

    /**
     * Get the connection name
     *
     * @return string|null
     */
    public static function getConnectionName(): ?string
    {
        return session()->get(SessionService::FILEVUER_CONNECTION_NAME);
    }

    /**
     * Set the connection name in the session
     *
     * @param string $name
     */
    public static function setConnectionName(string $name): void
    {
        session()->put(SessionService::FILEVUER_CONNECTION_NAME, $name);
    }

    /**
     * Set the connection to logged in
     */
    public static function setLoggedInTrue(): void
    {
        session()->put(SessionService::FILEVUER_LOGGEDIN, true);
    }

    /**
     * Get the connection logged in status
     *
     * @return bool|null
     */
    public static function getLoggedIn(): ?bool
    {
        return session()->get(SessionService::FILEVUER_LOGGEDIN);
    }

    public static function forget(): void
    {
        session()->forget([
            SessionService::FILEVUER_LOGGEDIN,
            SessionService::FILEVUER_CONNECTION_NAME,
        ]);
    }
}
