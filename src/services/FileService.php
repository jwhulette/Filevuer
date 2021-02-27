<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionService;

class FileService implements FileServiceInterface
{
    /**
     * Deletes one or multiple files.
     *
     * @param array $path
     *
     * @return bool
     */
    public function delete(array $path): bool
    {
        foreach ($path as $file) {
            $delete = Storage::disk(SessionService::getConnectionName())->delete($file);
            if (!$delete) {
                throw new Exception("Failed deleting file " . $file, 1);
            }
        }

        return true;
    }

    /**
     * Validates a path.
     *
     * @param string $path
     *
     * @return void
     * @throws InvalidArgumentException
     */
    protected function checkPath($path): void
    {
        if ('' == $path) {
            throw new InvalidArgumentException('Please specify a file path.');
        }
    }
}
