<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Illuminate\Support\Collection;

interface ConfigurationServiceInterface
{
    public function getConnectionsList(): Collection;

    public function getConnectionDisplayList(): Collection;

    public function getSelectedConnection(string $name): string;
}
