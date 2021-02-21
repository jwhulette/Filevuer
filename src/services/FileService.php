<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use InvalidArgumentException;
use Illuminate\Filesystem\FilesystemManager;

class FileService implements FileServiceInterface
{
    protected FilesystemManager $fileSystem;

    /**
     * @param FilesystemManager $fileSystem
     */
    public function __construct(FilesystemManager $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Get file contents from server.
     *
     * @param string|null $path
     *
     * @throws InvalidArgumentException
     *
     * @return string|null
     */
    public function contents(?string $path = ''): ?string
    {
        $this->checkPath($path);

        return $this->fileSystem->cloud()->get($path);
    }

    /**
     * Updates a file's contents.
     *
     * @param string|null $path
     * @param string|null $contents
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function update(?string $path = '', ?string $contents = ''): bool
    {
        $this->checkPath($path);

        return $this->fileSystem->cloud()->put($path, $contents);
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
            $this->fileSystem->cloud()->delete($file);
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
        return $this->fileSystem->cloud()->put($path, '');
    }

    /**
     * Validates a path.
     *
     * @param string $path
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function checkPath($path): void
    {
        if ('' == $path) {
            throw new InvalidArgumentException('Please specify a file path.');
        }
    }
}
