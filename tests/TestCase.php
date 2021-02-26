<?php

namespace Jwhulette\Filevuer\Tests;

use org\bovigo\vfs\vfsStream;
use Illuminate\Support\Facades\Config;
use org\bovigo\vfs\vfsStreamDirectory;
use Jwhulette\Filevuer\FilevuerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected vfsStreamDirectory $vfs;

    public function setUp(): void
    {
        parent::setUp();

        $directory = [
            'Cdirectory1' => [
                'directoryA' => [],
                'Ztest.txt' => 'some text content',
                'Atest2.txt' => 'some text content',
            ],
            'ctest.txt' => 'some text',
            'Bdirectory2' => [],
            'Adirectory3' => [],
        ];

        $this->vfs = vfsStream::setup(sys_get_temp_dir(), null, $directory);

        Config::set('filesystems.disks.local.root', $this->vfs->url());
    }

    protected function getPackageProviders($app): array
    {
        return [
            FilevuerServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks', $this->dummyConnections());
    }

    protected function dummyConnections()
    {
        return [
            'local' => [
                'driver' => 'local',
                'root' => 'vfs',
            ],
            'ftp' => [
                'driver'   => 'ftp',
                'name'     => 'FTP1',
                'host'     => 'ftp.host1.com',
                'username' => 'ftp1',
                'password' => 'ftp',
                'port'     => 21,
                'home_dir' => "public_html",
            ],
            's3' => [
                [
                    'driver' => 's3',
                    'name'     => 'AWSS3',
                    'key'      => 'key',
                    'secret'   => 'serect',
                    'bucket'   => 'my-bucket',
                    'region'   => 'us-east-1',
                    'home_dir' => '/test',
                ],
            ]
        ];
    }
}
