<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Filesystem\FilesystemManager;

class DirectoryControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Mock our filesystem manager
        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'listContents', 'createDir', 'deleteDir'])
            ->getMock();

        $filesystem->method('cloud')
            ->will($this->returnSelf());

        $filesystem->method('listContents')
            ->willReturn($this->dummyListingPreformat());

        $filesystem->method('createDir')
            ->willReturn(true);

        $filesystem->method('deleteDir')
            ->willReturn(true);

        $this->app->instance(FilesystemManager::class, $filesystem);
    }

    public function test_directory_index()
    {
        $response = $this->withSession($this->getSessionValues())
            ->get(route('filevuer.directory.index'));

        $response->assertStatus(200);

        $this->assertEquals($response->getContent(), json_encode(['listing' => $this->dummyListing()]));
    }

    public function test_create_directory()
    {
        $response = $this->withSession($this->getSessionValues())
            ->post(route('filevuer.directory.create'), ['path' => 'dir/subdir']);

        $response->assertStatus(201);

        $this->assertEquals('{"success":true}', $response->getContent());
    }

    public function test_delete_directory()
    {
        $response = $this->withSession($this->getSessionValues())
            ->delete(route('filevuer.directory.destroy'), ['path' => ['dir/subdir']]);

        $response->assertStatus(200);

        $this->assertEquals('{"success":"Directory deleted"}', $response->getContent());
    }
}
