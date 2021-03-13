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
     * @return Collection
     */
    public function getConnectionDisplayList(): Collection
    {
        $filevuerConnections = $this->getConnectionsList();

        $connections = collect(config('filesystems.disks'))
            ->map(function ($item, $key) use ($filevuerConnections) {
                // Get the disk info that we should use
                if ($filevuerConnections->contains($key)) {
                    return [
                        'name' => $item['name'] ?? $key
                    ];
                }
            })->filter(function ($value) {
                return $value !== null;
            });

        return $connections;
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
