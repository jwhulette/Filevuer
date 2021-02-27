<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Illuminate\Support\Facades\Config;
use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\SessionService;

class FileControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        SessionService::setConnectionName('local');

        SessionService::setLoggedInTrue();

        Config::set('filesystems.disks.local.root', $this->vfs->url());
    }

    public function test_delete_file()
    {
        $response = $this->delete(route('filevuer.file'), ['path' => ['ctest.txt']]);

        $response->assertStatus(200);

        $this->assertEquals('{"success":true}', $response->getContent());
    }
}
