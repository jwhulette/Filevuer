<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Jwhulette\Filevuer\Tests\TestCase;

class UploadFileControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_upload_no_files()
    {
        $response = $this->post(route('filevuer.upload'), [
            'path' => '/test',
            'files' => []
        ]);

        $response->assertStatus(500);
    }

    public function test_upload_failed()
    {
        $response = $this->post(route('filevuer.upload'), [
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

        $response = $this->post(route('filevuer.upload'), [
            'path'  => '/test',
            'files' => $files
        ]);

        $response->assertStatus(200);
    }

    public function test_upload_faild_zip()
    {
        $files = [
            UploadedFile::fake()->create('test.zip', 20000),
        ];

        $response = $this->post(route('filevuer.upload'), [
            'path'    => '/test/zip',
            'files'   => $files,
            'extract' => true,
        ]);

        $response->assertStatus(500);
    }
}
