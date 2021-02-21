<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Filesystem\FilesystemManager;
use Jwhulette\Filevuer\Services\SessionInterface;

class FilevuerControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_index_display_connections()
    {
        $response = $this->get(route('filevuer.index'));

        $response->assertOk();

        $response->assertSee("filevuer-main");

        $response->assertSee(":connections=");

        $response->assertSee("FTP1");

        $response->assertSee("AWS");

        $response->assertSee("logged-in");

        $response->assertSee("selected=");
    }

    public function testConnectAlreadyLoggedIn()
    {
        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'files'])
            ->getMock();

        $filesystem->method('cloud')->will($this->returnSelf());

        $filesystem->method('files')->willReturn([]);

        $this->app->instance(FilesystemManager::class, $filesystem);

        session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);

        $response = $this->withSession($this->getSessionValues())
            ->post(route('filevuer.index'), ['connection' => 'FTP1']);

        $response->assertOk();

        $this->assertEquals('true', $response->getContent());

        $response->assertSessionHas(SessionInterface::FILEVUER_LOGGEDIN, true);
    }

    public function testConnectSuccessFTP()
    {
        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'files'])
            ->getMock();

        $filesystem->method('cloud')->will($this->returnSelf());

        $filesystem->method('files')->willReturn([]);

        $this->app->instance(FilesystemManager::class, $filesystem);

        $response = $this->post(route('filevuer.index'), ['connection' => 'FTP1']);

        $response->assertOk();

        $this->assertEquals('true', $response->getContent());

        $response->assertSessionHas(SessionInterface::FILEVUER_LOGGEDIN, true);
    }

    public function testConnectSuccessS3()
    {
        $filesystem = $this->getMockBuilder(FilesystemManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['cloud', 'files'])
            ->getMock();

        $filesystem->method('cloud')->will($this->returnSelf());

        $filesystem->method('files')->willReturn([]);

        $this->app->instance(FilesystemManager::class, $filesystem);

        $response = $this->post(route('filevuer.index'), ['connection' => 'AWSS3']);

        $response->assertOk();

        $this->assertEquals('true', $response->getContent());

        $response->assertSessionHas(SessionInterface::FILEVUER_LOGGEDIN, true);
    }

    public function testConnectFailedFTP()
    {
        $response = $this->post(route('filevuer.index'), ['connection' => 'FTP1']);

        $response->assertOk();

        $this->assertEquals('false', $response->getContent());

        $response->assertSessionMissing(SessionInterface::FILEVUER_LOGGEDIN);
    }

    public function testConnectFailedS3()
    {
        $response = $this->post(route('filevuer.index'), ['connection' => 'AWSS3']);

        $response->assertOk();

        $this->assertEquals('false', $response->getContent());

        $response->assertSessionMissing(SessionInterface::FILEVUER_LOGGEDIN);
    }

    public function testConnectFailedUnkownDriver()
    {
        $response = $this->post(route('filevuer.index'), ['connection' => 'RACKSPACE']);

        $response->assertOk();

        $this->assertEquals('false', $response->getContent());

        $response->assertSessionMissing(SessionInterface::FILEVUER_LOGGEDIN);
    }

    public function testLogout()
    {
        $response = $this->get(route('filevuer.logout'));

        $response->assertStatus(302);

        $response->assertRedirect(route('filevuer.index'));

        $response->assertSessionMissing(SessionInterface::FILEVUER_DRIVER);

        $response->assertSessionMissing(SessionInterface::FILEVUER_LOGGEDIN);

        $response->assertSessionMissing(SessionInterface::FILEVUER_DATA);

        $response->assertSessionMissing(SessionInterface::FILEVUER_HOME_DIR);

        $response->assertSessionMissing(SessionInterface::FILEVUER_CONNECTION_NAME);
    }
}
