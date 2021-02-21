<?php

namespace Jwhulette\Filevuer\Tests;

use Jwhulette\Filevuer\FileVuerServiceProvider;
use Jwhulette\Filevuer\Services\SessionInterface;
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
            FileVuerServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filevuer.connections', $this->dummyConnections());
    }

    protected function getSessionValues()
    {
        return [
            SessionInterface::FILEVUER_DRIVER => 'ftp',
            SessionInterface::FILEVUER_LOGGEDIN => 'true',
            SessionInterface::FILEVUER_DATA => encrypt([
                'name'     => 'FTP1',
                'host'     => 'ftp.host1.com',
                'username' => 'ftp1',
                'password' => 'ftp',
                'port'     => 21,
                'home_dir' => "public_html",
            ]),
            SessionInterface::FILEVUER_HOME_DIR => 'public_html',
            SessionInterface::FILEVUER_CONNECTION_NAME => 'FTP1'
        ];
    }

    protected function getSessionValuesS3()
    {
        return [
            SessionInterface::FILEVUER_DRIVER => 's3',
            SessionInterface::FILEVUER_LOGGEDIN => 'true',
            SessionInterface::FILEVUER_DATA =>                 [
                'name'     => 'AWSS3',
                'key'      => 'aul;kjaer',
                'secret'   => 'alkdfjiei',
                'bucket'   => 'my-bucket',
                'region'   => 'us-east-1',
                'home_dir' => '/test',
            ],
            SessionInterface::FILEVUER_HOME_DIR => '/test',
            SessionInterface::FILEVUER_CONNECTION_NAME => 'AWSS3'
        ];
    }

    protected function dummyConnections()
    {
        return [
            'FTP' => [
                [
                    'name'     => 'FTP1',
                    'host'     => 'ftp.host1.com',
                    'username' => 'ftp1',
                    'password' => 'ftp',
                    'port'     => 21,
                    'home_dir' => "public_html",
                ],
            ],

            'S3' => [
                [
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
