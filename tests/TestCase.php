<?php

namespace Jwhulette\Filevuer\Tests;

use Jwhulette\Filevuer\FilevuerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
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
                    'key'      => 'aul;kjaer',
                    'secret'   => 'alkdfjiei',
                    'bucket'   => 'my-bucket',
                    'region'   => 'us-east-1',
                    'home_dir' => '/test',
                ],
            ]
        ];
    }

    protected function dummyListing()
    {
        return [
            [
                'type'     => 'dir',
                'path'     => 'Directory A',
                'dirname'  => '',
                'basename' => 'Directory A',
                'filename' => 'Directory A',
            ],
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
            ],
            [
                'type'       => 'file',
                'path'       => 'fileC.txt',
                'visibility' => 'public',
                'size'       => '0 bytes',
                'dirname'    => '',
                'basename'   => 'fileC.txt',
                'extension'  => 'txt',
                'filename'   => 'fileC',
            ],
        ];
    }

    protected function dummyListingPreformat()
    {
        return [
            [
                'type'     => 'dir',
                'path'     => 'Directory A',
                'dirname'  => '',
                'basename' => 'Directory A',
                'filename' => 'Directory A',
            ],
            [
                'type'       => 'file',
                'path'       => 'fileA.txt',
                'visibility' => 'public',
                'size'       => 30,
                'dirname'    => '',
                'basename'   => 'fileA.txt',
                'extension'  => 'txt',
                'filename'   => 'fileA',
            ],
            [
                'type'       => 'file',
                'path'       => 'fileB.txt',
                'visibility' => 'public',
                'size'       => 30,
                'dirname'    => '',
                'basename'   => 'fileB.txt',
                'extension'  => 'txt',
                'filename'   => 'fileB',
            ],
            [
                'type'       => 'file',
                'path'       => 'fileC.txt',
                'visibility' => 'public',
                'size'       => 0,
                'dirname'    => '',
                'basename'   => 'fileC.txt',
                'extension'  => 'txt',
                'filename'   => 'fileC',
            ],
        ];
    }
}
