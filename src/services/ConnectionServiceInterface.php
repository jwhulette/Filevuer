<?php
declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

interface ConnectionServiceInterface
{
    public function connectToService(?array $connection): bool;
}
