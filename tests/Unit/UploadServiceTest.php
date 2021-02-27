<?php

namespace Jwhulette\Filevuer\Tests\Unit;

use Exception;
use Illuminate\Http\UploadedFile;
use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\UploadService;

class UploadServiceTest extends TestCase
{
    protected $tmpPath;

    protected UploadService $uploadService;

    public function setUp(): void
    {
        parent::setUp();

        $this->tmpPath = sys_get_temp_dir();

        $this->uploadService = new UploadService();
    }

    public function test_upload_service_upload_single_file()
    {
        Storage::fake('local');

        $files = [
            UploadedFile::fake()->create('document.pdf', 20000),
        ];

        $this->uploadService->uploadFiles('/test', $files);

        $this->assertTrue(true);
    }

    public function test_upload_service_upload_single_zip_file()
    {
        $testZip = $this->createTestZip();

        $this->uploadService->uploadFiles('/zip_files', [$testZip], false);

        $this->assertTrue(true);
    }

    public function test_upload_service_upload_single_zip_file_extract()
    {
        Storage::fake('local');

        $testZip = $this->createTestZip();

        $this->uploadService->uploadFiles('/test', [$testZip], true);

        $this->assertTrue(true);
    }

    public function test_upload_service_upload_zip_file_extract_fail()
    {
        $this->expectException(Exception::class);

        $testZip = $this->createCorruptTestZip();

        $this->uploadService->uploadFiles('/test', [$testZip], true);

        $this->assertTrue(true);
    }

    private function copyZip($file)
    {
        copy(dirname(__DIR__) . '/Assets/' . $file, $this->tmpPath . $file);

        chmod($this->tmpPath . $file, 0777);
    }

    private function createTestZip()
    {
        $this->copyZip('/testArchive.zip');

        return new UploadedFile(
            $this->tmpPath . '/testArchive.zip',
            'testArchive.zip',
            'application/zip',
            3066,
            true
        );
    }

    private function createCorruptTestZip()
    {
        $this->copyZip('/testCorruptArchive.zip');

        return new UploadedFile(
            $this->tmpPath . '/testCorruptArchive.zip',
            'testCorruptArchive.zip',
            'application/zip',
            3066,
            true
        );
    }
}
