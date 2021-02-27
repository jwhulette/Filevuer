<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Unit;

use InvalidArgumentException;
use Illuminate\Support\Collection;
use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\ConfigurationService;

class ConfigurationServiceTest extends TestCase
{
    protected ConfigurationService $configurationService;

    public function setUp(): void
    {
        parent::setUp();

        $this->configurationService = new ConfigurationService();
    }

    public function test_get_connection_list_view()
    {
        $disks = $this->configurationService->getConnectionDisplayList();

        $this->assertJson($disks);
    }

    public function test_get_avaliable_disks()
    {
        $disks = $this->configurationService->getConnectionsList();

        $this->assertInstanceOf(Collection::class, $disks);
    }

    public function test_get_selected_connection()
    {
        $disk = $this->configurationService->getSelectedConnection('sftp');

        $this->assertSame($disk, 'sftp');
    }

    public function test_get_selected_connection_exception()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->configurationService->getSelectedConnection('nodisk');
    }
}
