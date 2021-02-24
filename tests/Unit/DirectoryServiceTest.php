<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Unit;

use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\SessionService;
use Jwhulette\Filevuer\Services\DirectoryService;

class DirectoryServiceTest extends TestCase
{
    protected DirectoryService $directoryService;

    public function setUp(): void
    {
        parent::setUp();

        $this->directoryService = new DirectoryService();

        SessionService::setConnectionName('local');

        SessionService::setLoggedInTrue();
    }

    public function test_list_directories()
    {
        $directories = $this->directoryService->listing();

        dd($directories);
    }
}
