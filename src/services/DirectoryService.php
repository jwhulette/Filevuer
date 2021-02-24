<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionService;
use Jwhulette\Filevuer\Services\DirectoryServiceInterface;

/**
 * Directory Service Class
 */
class DirectoryService implements DirectoryServiceInterface
{
    protected ConnectionServiceInterface $connectionService;

    /**
     * List the directory contenets
     *
     * @param string|null $path
     *
     * @return array
     */
    public function listing(?string $path = '/'): array
    {
        $contents = Storage::disk(SessionService::getConnectionName())->directories($path);

        $contents = $this->sortForListing($contents);

        $contents = $this->formatFileSize($contents);

        return $contents;
    }

    /**
     * Delete a all files in a folder
     *
     * @param array|null $path
     *
     * @return bool
     */
    public function delete(?array $path): bool
    {
        foreach ($path as $dir) {
            Storage::disk(SessionService::getConnectionName())->deleteDirectory($dir);
        }

        return true;
    }

    /**
     * Creates an empty directory.
     *
     * @param string $path
     *
     * @return bool
     */
    public function create(string $path): bool
    {
        $path = $this->getFullPath($path);

        return Storage::disk(SessionService::getConnectionName())->makeDirectory($path);
    }

    /**
     * Sort the listing by type and filename.
     *
     * @param array $contents
     *
     * @return array
     */
    protected function sortForListing(array $contents): array
    {
        usort($contents, function ($typeA, $typeB) {
            // Sort by type
            $comparison = strcmp($typeA, $typeB);

            if (0 !== $comparison) {
                return $comparison;
            }

            // Sort by name
            return strcmp($typeA['filename'], $typeB['filename']);
        });

        return $contents;
    }

    /**
     * Format the filesize human readable.
     *
     * @param array $contents
     *
     * @return array
     */
    protected function formatFileSize(array $contents): array
    {
        return array_map(function ($item) {
            if (isset($item['size'])) {
                $item['size'] = $this->formatBytes($item['size']);
            }

            return $item;
        }, $contents);
    }

    /**
     * Format bytes as human readable filesize.
     *
     * @param int  $size
     * @param int $precision
     *
     * @return string
     */
    public function formatBytes(int $size, int $precision = 2): string
    {
        if ($size > 0) {
            $size = (int) $size;

            $base = log($size) / log(1024);

            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size . ' bytes';
    }
}
