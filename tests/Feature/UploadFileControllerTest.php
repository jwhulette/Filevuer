<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use RuntimeException;
use Illuminate\Http\UploadedFile;
use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\UploadServiceInterface;

class UploadFileControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_upload_no_files()
    {
        $response = $this->withSession($this->getSessionValues())->post(route('filevuer.upload'), [
            'path' => '/test',
            'files' => []
        ]);

        $response->assertStatus(500);
    }

    public function test_upload_failed()
    {
        $upload = $this->createMock(UploadServiceInterface::class);
        $upload->method('uploadFiles');
        $this->app->instance(UploadServiceInterface::class, $upload);

        $response = $this->withSession($this->getSessionValues())->post(route('filevuer.upload'), [
            'path'  => '/test',
            'files' => null
        ]);

        $response->assertStatus(500);
    }

    public function test_file_upload()
    {
        $files = [
            UploadedFile::fake()->create('document.pdf', 20000),
        ];
        $upload = $this->createMock(UploadServiceInterface::class);

        $upload->method('uploadFiles');

        $this->app->instance(UploadServiceInterface::class, $upload);

        $response = $this->withSession($this->getSessionValues())->post(route('filevuer.upload'), [
            'path'  => '/test',
            'files' => $files
        ]);

        $response->assertStatus(200);
    }

    public function test_upload_faild_zip()
    {
        $files = [
            UploadedFile::fake()->create('document.pdf', 20000),
        ];
        $upload = $this->createMock(UploadServiceInterface::class);

        $upload->method('uploadFiles')
            ->will($this->throwException(new RuntimeException()));

        $this->app->instance(UploadServiceInterface::class, $upload);

        $response = $this->withSession($this->getSessionValues())->post(route('filevuer.upload'), [
            'path'    => '/test',
            'files'   => $files,
            'extract' => true,
        ]);

        $response->assertStatus(500);
    }
}
