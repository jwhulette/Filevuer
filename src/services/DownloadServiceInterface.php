<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface DownloadServiceInterface
{
    public function setHash(array $paths): string;

    public function getDownload(string $hash): StreamedResponse;
}
