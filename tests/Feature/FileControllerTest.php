<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Filesystem\FilesystemManager;
use Jwhulette\Filevuer\Services\SessionInterface;

class FileControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'read', 'put', 'delete'])
            ->getMock();

        $filesystem->method('cloud')
            ->will($this->returnSelf());

        $filesystem->method('read')
            ->willReturn('xyz');

        $filesystem->method('put')
            ->willReturn(true);

        $filesystem->method('delete')
            ->willReturn(true);

        $this->app->instance(FilesystemManager::class, $filesystem);
    }

    public function test_show_files()
    {
        $response = $this->withSession($this->getSessionValues())
            ->get(route('filevuer.file'), ['path' => '']);

        $response->assertStatus(200);

        $this->assertEquals(json_encode([
            'contents' => 'xyz',
            'download' => false
        ]), $response->getContent());
    }

    public function test_failed_to_show_files()
    {
        session()->forget(SessionInterface::FILEVUER_HOME_DIR);

        $response = $this->put(route('filevuer.file'), ['path' => null]);

        $response->assertStatus(500);
    }


    public function test_create_file()
    {
        $response = $this->withSession($this->getSessionValues())
            ->post(route('filevuer.file'), ['path' => '']);

        $response->assertStatus(201);

        $this->assertEquals('{"success":true}', $response->getContent());
    }

    public function test_update_file()
    {
        $response = $this->withSession($this->getSessionValues())
            ->put(route('filevuer.file'), ['path' => '', 'contents' => 'new contents']);

        $response->assertStatus(200);

        $this->assertEquals('{"success":true}', $response->getContent());
    }

    public function test_delete_file()
    {
        $response = $this->withSession($this->getSessionValues())
            ->delete(route('filevuer.file'), ['path' => ['test.txt']]);

        $response->assertStatus(200);

        $this->assertEquals('{"success":true}', $response->getContent());
    }
}
