<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Unit;

use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\SessionService;
use Jwhulette\Filevuer\Services\ConnectionService;

class ConnectionServiceTest extends TestCase
{
    protected ConnectionService $connectionService;

    public function setUp(): void
    {
        parent::setUp();

        $this->connectionService = new ConnectionService();
    }

    public function test_connect_to_disk()
    {
        $connection = $this->connectionService->connectToService('ftp');

        $this->assertTrue($connection);

        $this->assertSame(SessionService::getConnectionName(), 'ftp');

        $this->assertSame(SessionService::getLoggedIn(), true);
    }

    public function test_logout_of_disk()
    {
        SessionService::setConnectionName('ftp');

        SessionService::setLoggedInTrue();

        $this->connectionService->logout();

        $this->assertNull(SessionService::getConnectionName());

        $this->assertNull(SessionService::getLoggedIn());
    }
}
