<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

interface FileServiceInterface
{
    public function contents(?string $path = ''): ?string;

    public function update(?string $path = '', ?string $contents = ''): bool;

    public function delete(array $path): bool;

    public function create(string $path): bool;
}
