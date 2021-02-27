<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Tests\Feature;

use Jwhulette\Filevuer\Tests\TestCase;
use Jwhulette\Filevuer\Services\SessionService;

class DirectoryControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        SessionService::setConnectionName('local');

        SessionService::setLoggedInTrue();
    }

    public function test_directory_index()
    {
        $response = $this->get(route('filevuer.directory.index'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'listing' => [
                    [
                        'type' => 'dir',
                        "path" => "Adirectory3",
                        "dirname" => "",
                        "basename" => "Adirectory3",
                        "filename" => "Adirectory3"
                    ]
                ]
            ]);
    }

    public function test_create_directory()
    {
        $response = $this->post(route('filevuer.directory.create'), ['path' => 'dir/subdir']);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    public function test_delete_directory()
    {
        $response = $this->delete(route('filevuer.directory.destroy'), ['path' => 'Adirectory3']);

        $response->assertStatus(200)
            ->assertJson(['success' => 'Directory deleted']);
    }
}
