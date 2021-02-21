<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\FilesystemManager;
use Jwhulette\Filevuer\Services\SessionInterface;
use Jwhulette\Filevuer\Traits\SessionDriverTrait;

class ConnectionService implements ConnectionServiceInterface
{
    use SessionDriverTrait;

    /**
     * @var FilesystemManager
     */
    protected $fileSystem;

    /**
     * __construct
     *
     * @param FilesystemManager $fileSystem
     */
    public function __construct(FilesystemManager $fileSystem)
    {
        $this->fileSystem = $fileSystem;

        // @codeCoverageIgnoreStart
        if (!extension_loaded('ftp')) {
            throw new Exception('FTP extension is not loaded!');
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param array|null $connection
     *
     * @return bool
     */
    public function connectToService(?array $connection): bool
    {
        try {
            if (is_null($connection)) {
                throw new Exception('Unkown connection driver');
            }

            session()->put(SessionInterface::FILEVUER_DRIVER, $connection['driver']);

            $this->setSessionData($connection);

            $this->applyConfiguration();

            // Test connection
            $this->fileSystem->cloud()->files('/');

            session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);

            return true;
        } catch (Exception $error) {
            Log::error($error->getMessage());
        }

        return false;
    }

    /**
     * Remove session values
     *
     * @return void
     */
    public function logout()
    {
        session()->forget([
            SessionInterface::FILEVUER_DRIVER,
            SessionInterface::FILEVUER_LOGGEDIN,
            SessionInterface::FILEVUER_DATA,
            SessionInterface::FILEVUER_HOME_DIR,
            SessionInterface::FILEVUER_CONNECTION_NAME,
        ]);
    }
}
