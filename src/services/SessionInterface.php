<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

interface SessionInterface
{
    public const FILEVUER_DRIVER          = 'filevuerDriver';
    public const FILEVUER_LOGGEDIN        = 'filevuerLoggedIn';
    public const FILEVUER_DATA            = 'filevuerData';
    public const FILEVUER_HOME_DIR        = 'filevuerHomeDirectory';
    public const FILEVUER_CONNECTION_NAME = 'filevuerConnectionName';
    public const FILEVUER_DOWNLOAD        = 'filevuerDownload';
}
