<?php
declare(strict_types=1);

namespace jwhulette\filevuer\services;

interface ConnectionServiceInterface
{
    public function connectToService(?array $connection): bool;
}
