<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use ZipStream\ZipStream;
use Illuminate\Support\Str;
use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Filesystem\FilesystemManager;
use Jwhulette\Filevuer\Services\SessionInterface;
use Jwhulette\Filevuer\Services\DownloadServiceInterface;

class DownloadControllerTest extends TestCase
{
    public function test_generate()
    {
        $response = $this->withSession($this->getSessionValues())
            ->post(route('filevuer.generate'), ['path' => ['/test', '/test2']]);

        $response->assertSessionHas(SessionInterface::FILEVUER_DOWNLOAD . $response->getContent());

        $response->assertStatus(200);
    }

    public function test_download_single_file()
    {
        session()->put(SessionInterface::FILEVUER_DOWNLOAD . '123456', [[
            'type'       => 'file',
            'path'       => 'fileA.txt',
            'visibility' => 'public',
            'size'       => '30 bytes',
            'dirname'    => '',
            'basename'   => 'fileA.txt',
            'extension'  => 'txt',
            'filename'   => 'fileA',
        ]]);
        $response = $this->withSession($this->getSessionValues())
            ->get(route('filevuer.download', ['hash' => '123456']));

        $response->assertStatus(200);

        $this->assertEquals('application/octet-stream;', $response->headers->get('Content-Type'));

        $this->assertTrue(Str::contains(
            $response->headers->get('Content-Disposition'),
            'attachment; filename='
        ));
    }

    public function test_download_mulit_file()
    {
        $files = $this->dummyListing();

        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'listContents', 'readStream'])
            ->getMock();

        $filesystem->method('cloud')
            ->will($this->returnSelf());

        $filesystem->method('listContents')
            ->willReturn($files);

        $filesystem->method('readStream')
            ->willReturn('xyz');

        $this->app->instance(FilesystemManager::class, $filesystem);

        $zipStream = $this->createMock(ZipStream::class);

        $zipStream->method('addFileFromStream');

        $this->app->instance(ZipStream::class, $filesystem);

        $service = app()->make(DownloadServiceInterface::class);

        session()->put(SessionInterface::FILEVUER_DOWNLOAD . '123456', $files);

        $response = $this->withSession($this->getSessionValues())
            ->get(route('filevuer.download', ['hash' => '123456']));

        $response->assertStatus(200);

        $this->assertEquals('application/octet-stream;', $response->headers->get('Content-Type'));

        $this->assertTrue(Str::contains(
            $response->headers->get('Content-Disposition'),
            'attachment; filename='
        ));
    }
}
