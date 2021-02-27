<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Illuminate\Support\Str;
use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\SessionService;

class DownloadControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_generate_download()
    {
        $response = $this->post(route('filevuer.generate'), ['path' => ['/test', '/test2']]);

        $response->assertStatus(200)
            ->assertSessionHas(SessionService::FILEVUER_DOWNLOAD . $response->getContent());
    }

    public function test_download_single_file()
    {
        session()->put(SessionService::FILEVUER_DOWNLOAD . '123456', [[
            'type'       => 'file',
            'path'       => 'fileA.txt',
            'visibility' => 'public',
            'size'       => '30 bytes',
            'dirname'    => '',
            'basename'   => 'fileA.txt',
            'extension'  => 'txt',
            'filename'   => 'fileA',
        ]]);
        $response = $this->get(route('filevuer.download', ['hash' => '123456']));

        $response->assertStatus(200);

        $this->assertEquals('application/octet-stream;', $response->headers->get('Content-Type'));

        $this->assertTrue(Str::contains(
            $response->headers->get('Content-Disposition'),
            'attachment; filename='
        ));
    }

    public function test_download_mulitple_files()
    {
        $files = [
            $this->directory['Cdirectory1']['Ztest.txt'],
            $this->directory['Cdirectory1']['Atest2.txt']
        ];

        SessionService::setConnectionName('local');

        session()->put(SessionService::FILEVUER_DOWNLOAD . '123456', $files);

        $response = $this->get(route('filevuer.download', ['hash' => '123456']));

        $response->assertStatus(200);

        $this->assertEquals('application/octet-stream;', $response->headers->get('Content-Type'));

        $this->assertTrue(Str::contains(
            $response->headers->get('Content-Disposition'),
            'attachment; filename='
        ));

        $this->assertTrue(Str::contains(
            $response->headers->get('Content-Disposition'),
            '.zip'
        ));
    }
}
