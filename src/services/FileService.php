<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionService;

class FileService implements FileServiceInterface
{
    /**
     * Get file contents from server.
     *
     * @param string|null $path
     *
     * @return string|null
     *
     * @throws InvalidArgumentException
     */
    public function contents(?string $path = ''): ?string
    {
        $this->checkPath($path);

        return Storage::disk(SessionService::getConnectionName())->get($path);
    }

    /**
     * Updates a file's contents.
     *
     * @param string|null $path
     * @param string|null $contents
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function update(?string $path = '', ?string $contents = ''): bool
    {
        $this->checkPath($path);

        return Storage::disk(SessionService::getConnectionName())->put($path, $contents);
    }

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
     * Creates an empty file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function create(string $path): bool
    {
        return Storage::disk(SessionService::getConnectionName())->put($path, '');
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
