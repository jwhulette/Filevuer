<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jwhulette\Filevuer\Services\DirectoryServiceInterface;

/**
 * DirectoryController Class
 */
class DirectoryController extends Controller
{
    private DirectoryServiceInterface $directory;

    /**
     * @param DirectoryServiceInterface $directory
     */
    public function __construct(DirectoryServiceInterface $directory)
    {
        $this->directory = $directory;
    }

    /**
     * List directorys.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $path = $request->get('path', '/');

        return response()->json([
            'listing' => $this->directory->listing($path),
        ], 200);
    }

    /**
     * Create directroy.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $path = $request->get('path', '');

        return response()->json([
            'success' => $this->directory->create($path),
        ], 201);
    }

    /**
     * Delete directory.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $path = $request->input('path', null);

        $this->directory->delete($path);

        return response()->json([
            'success' => 'Directory deleted',
        ], 200);
    }
}
