<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

interface UploadServiceInterface
{
    public function uploadFiles(string $path, array $files, bool $extract = false): void;
}
