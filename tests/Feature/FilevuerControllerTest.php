<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Jwhulette\Filevuer\Tests\TestCase;
use Illuminate\Filesystem\FilesystemManager;
use Jwhulette\Filevuer\Services\SessionService;

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

        $response->assertSee("local");

        $response->assertSee("sftp");

        $response->assertSee("logged-in");

        $response->assertSee("selected=");
    }

    public function test_connect_already_logged_in()
    {
        session()->put(SessionService::FILEVUER_LOGGEDIN, true);

        $response = $this->post(route('filevuer.index'), ['connection' => 'sftp']);

        $response->assertOk()
            ->assertSessionHas(SessionService::FILEVUER_LOGGEDIN, true);

        $this->assertEquals('true', $response->getContent());
    }

    public function test_connect_success_disk()
    {
        $response = $this->post(route('filevuer.index'), ['connection' => 'sftp']);

        $this->assertEquals('true', $response->getContent());

        $response->assertSessionHas(SessionService::FILEVUER_LOGGEDIN, true);
    }

    public function test_connect_failed_disk()
    {
        $response = $this->post(route('filevuer.index'), ['connection' => 'FTP1']);

        $response->assertOk()
            ->assertJson(['error' => 'Unable to connect']);

        $response->assertSessionMissing(SessionService::FILEVUER_LOGGEDIN);
    }

    public function test_logout()
    {
        $response = $this->get(route('filevuer.logout'));

        $response->assertStatus(302)
            ->assertRedirect(route('filevuer.index'))
            ->assertSessionMissing(SessionService::FILEVUER_LOGGEDIN)
            ->assertSessionMissing(SessionService::FILEVUER_CONNECTION_NAME);
    }
}
