<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jwhulette\Filevuer\Traits\SessionDriverTrait;
use Jwhulette\Filevuer\Services\FileServiceInterface;

/**
 * FileController Class
 */
class FileController extends Controller
{
    use SessionDriverTrait;

    private FileServiceInterface $fileservice;

    /**
     * @param FileServiceInterface $fileservice
     */
    public function __construct(FileServiceInterface $fileservice)
    {
        $this->fileservice = $fileservice;
    }

    /**
     * Show the files
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $path     = $request->get('path', '');

        $path     = $this->getFullPath($path);

        $contents = $this->fileservice->contents($path);

        $isBinary = false === mb_detect_encoding($contents);

        if ($isBinary) {
            $contents = base64_encode($contents);
        }

        return response()->json([
            'contents' => $contents,
            'download' => $isBinary,
        ], 200);
    }

    /**
     * Create a new file
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $path = $request->get('path', '');

        $path = $this->getFullPath($path);

        return response()->json([
            'success' => $this->fileservice->create($path),
        ], 201);
    }

    /**
     * Update a file
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $path     = $request->get('path', '');

        $path     = $this->getFullPath($path);

        $contents = $request->get('contents', '');

        return response()->json([
            'success' => $this->fileservice->update($path, $contents),
        ], 200);
    }

    /**
     * Delete a file
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $path = $request->input('path', null);

        return response()->json([
            'success' => $this->fileservice->delete($path),
        ], 200);
    }
}
