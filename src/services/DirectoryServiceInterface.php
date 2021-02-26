<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Illuminate\Support\Collection;

interface DirectoryServiceInterface
{
    public function listing(?string $path = '/'): Collection;

    public function delete(string $dir): bool;

    public function create(string $path): bool;
}
