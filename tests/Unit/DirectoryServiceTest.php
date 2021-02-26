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

    public function test_service_list_directories()
    {
        $directories = $this->directoryService->listing();

        $listing = $directories->pluck('basename')->toArray();

        $expected = [
            "Adirectory3",
            "Bdirectory2",
            "Cdirectory1",
            "folders",
            "ctest.txt",
        ];

        $this->assertSame($expected, $listing);

        $this->assertSame($directories[4]['size'], '9 bytes');
    }

    public function test_service_delete_directory()
    {
        $directories = $this->directoryService->listing();

        $dir = $directories->pluck('path')->first();

        $actual = $this->directoryService->delete($dir);

        $this->assertTrue($actual);
    }

    public function test_service_create_directory()
    {
        $actual = $this->directoryService->create('Bdirectory2/New Directory');

        $this->assertTrue($actual);
    }
}
