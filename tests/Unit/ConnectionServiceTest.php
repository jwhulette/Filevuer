<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Unit;

use Exception;
use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionInterface;
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

        $this->assertSame(session()->get(SessionInterface::FILEVUER_CONNECTION_NAME), 'ftp');

        $this->assertSame(session()->get(SessionInterface::FILEVUER_LOGGEDIN), true);
    }

    public function test_logout_of_disk()
    {
        session()->put(SessionInterface::FILEVUER_CONNECTION_NAME, 'ftp');

        session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);

        $this->connectionService->logout();

        $this->assertNull(session()->get(SessionInterface::FILEVUER_CONNECTION_NAME));

        $this->assertNull(session()->get(SessionInterface::FILEVUER_LOGGEDIN));
    }
}
