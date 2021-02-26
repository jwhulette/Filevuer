<?php

namespace Jwhulette\Filevuer\Tests\Unit;

use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionService;
use Jwhulette\Filevuer\Services\DownloadService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadServiceTest extends TestCase
{
    protected DownloadService $downloadService;

    public function setUp(): void
    {
        parent::setUp();

        $this->downloadService = new DownloadService();
    }

    public function test_download_service_set_hash()
    {
        $paths = ['my/test/path'];

        $actual = $this->downloadService->setHash($paths);

        $this->assertIsString($actual);
    }

    public function test_download_service_single_file_download()
    {
        $paths = [[
            'type'       => 'file',
            'path'       => 'fileA.txt',
            'visibility' => 'public',
            'size'       => '30 bytes',
            'dirname'    => '',
            'basename'   => 'fileA.txt',
            'extension'  => 'txt',
            'filename'   => 'fileA',
        ]];

        $hash = $this->downloadService->setHash($paths);

        $actual = $this->downloadService->getDownload($hash);

        $this->assertInstanceOf(StreamedResponse::class, $actual);
    }

    public function test_download_service_multiple_file_download()
    {
        session()->put(SessionService::FILEVUER_CONNECTION_NAME, 'test');

        $paths = [
            [
                'type'       => 'file',
                'path'       => 'fileA.txt',
                'visibility' => 'public',
                'size'       => '30 bytes',
                'dirname'    => '',
                'basename'   => 'fileA.txt',
                'extension'  => 'txt',
                'filename'   => 'fileA',
            ],
            [
                'type'       => 'file',
                'path'       => 'fileB.txt',
                'visibility' => 'public',
                'size'       => '30 bytes',
                'dirname'    => '',
                'basename'   => 'fileB.txt',
                'extension'  => 'txt',
                'filename'   => 'fileB',
            ]
        ];

        $hash = $this->downloadService->setHash($paths);

        $actual = $this->downloadService->getDownload($hash);

        $this->assertInstanceOf(StreamedResponse::class, $actual);
    }
}
