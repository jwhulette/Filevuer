<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

interface FileServiceInterface
{
    public function delete(array $path): bool;
}
