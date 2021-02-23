<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use InvalidArgumentException;
use Illuminate\Support\Collection;

class ConfigurationService implements ConfigurationServiceInterface
{
    /**
     * @return Collection
     */
    public function getConnectionsList(): Collection
    {
        return collect(config('filevuer.disks'));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getSelectedConnection(string $name): string
    {
        $filesystem = config('filesystems.disks');

        $disk = collect($filesystem)->first(function ($item) use ($name) {
            return $item === $name;
        });

        if (\is_null($disk)) {
            throw new InvalidArgumentException('Unkown filesystem disk');
        }

        return $disk;
    }
}
