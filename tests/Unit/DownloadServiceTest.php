<?php

namespace Jwhulette\Filevuer\Tests\Unit;

use ZipStream\ZipStream;
use Illuminate\Http\UploadedFile;
use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Filesystem\FilesystemManager;
use Jwhulette\Filevuer\Services\SessionInterface;
use Jwhulette\Filevuer\Services\DownloadServiceInterface;

class DownloadServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testAddFilesToZip()
    {
        $file = $this->dummyListing()[0];

        $files = $this->dummyListing();

        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'listContents', 'readStream'])
            ->getMock();

        $filesystem->method('cloud')->will($this->returnSelf());

        $filesystem->method('listContents')->willReturn($files);

        $filesystem->method('readStream')->willReturn('xyz');

        $this->app->instance(FilesystemManager::class, $filesystem);

        $zipStream = $this->createMock(ZipStream::class);

        $this->app->instance(ZipStream::class, $filesystem);

        $service = app()->make(DownloadServiceInterface::class);

        session()->put(SessionInterface::FILEVUER_CONNECTION_NAME, 'testZip');

        $service->addFilesToZip($file, $zipStream);

        $this->assertTrue(true);
    }
}
