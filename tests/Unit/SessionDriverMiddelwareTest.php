<?php

namespace Jwhulette\Filevuer\Tests\Unit;

use Illuminate\Http\Request;
use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\middleware\SessionDriver;
use Jwhulette\Filevuer\Services\SessionInterface;

class SessionDriverMiddelwareTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Removing');
    }

    public function testMiddleware()
    {
        $request = new Request();

        $middleware = new SessionDriver();

        session()->put(SessionInterface::FILEVUER_LOGGEDIN, true);

        session()->put($this->getSessionValues());

        $middleware->handle($request, function ($request) {
            $this->assertEquals('ftp', session()->get(SessionInterface::FILEVUER_DRIVER));
        });
    }
}
