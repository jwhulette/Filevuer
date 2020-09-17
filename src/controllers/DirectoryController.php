<?php
declare(strict_types = 1);

namespace Jwhulette\Filevuer\controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use jwhulette\filevuer\services\DirectoryServiceInterface;

/**
 * DirectoryController Class
 */
class DirectoryController extends Controller
{
    private DirectoryServiceInterface $directory;

    /**
     * __construct.
     *
     * @param DirectoryServiceInterface $directory
     */
    public function __construct(DirectoryServiceInterface $directory)
    {
        $this->directory = $directory;
    }

    /**
     * List directorys.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        $path = $request->get('path', '/');

        return response([
            'listing' => $this->directory->listing($path),
        ], 200);
    }

    /**
     * Create directroy.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request): Response
    {
        $path = $request->get('path', '');

        return response([
            'success' => $this->directory->create($path),
        ], 201);
    }

    /**
     * Delete directory.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request): Response
    {
        $path = $request->input('path', null);

        $this->directory->delete($path);

        return response([
            'success' => 'Directory deleted',
        ], 200);
    }
}
