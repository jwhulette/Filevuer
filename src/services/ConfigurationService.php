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
     * @return string
     */
    public function getConnectionDisplayList(): string
    {
        return $this->getConnectionsList()->toJson();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getSelectedConnection(string $name): string
    {
        $filesystem = collect(config('filesystems.disks'));

        $disk = $filesystem->first(function ($item, $key) use ($name) {
            return $key === $name;
        });

        if (\is_null($disk)) {
            throw new InvalidArgumentException('Unkown filesystem disk');
        }

        return $name;
    }
}
