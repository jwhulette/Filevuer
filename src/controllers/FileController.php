<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jwhulette\Filevuer\Traits\SessionDriverTrait;
use Jwhulette\Filevuer\Services\FileServiceInterface;

/**
 * FileController Class
 */
class FileController extends Controller
{
    use SessionDriverTrait;

    /**
     * @var FileServiceInterface
     */
    private $fileservice;

    /**
     * @param FileRepository $fileservice
     */
    public function __construct(FileServiceInterface $fileservice)
    {
        $this->fileservice = $fileservice;
    }

    /**
     * Show the files
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        $path     = $request->get('path', '');

        $path     = $this->getFullPath($path);

        $contents = $this->fileservice->contents($path);

        $isBinary = false === mb_detect_encoding($contents);

        if ($isBinary) {
            $contents = base64_encode($contents);
        }

        return [
            'contents' => $contents,
            'download' => $isBinary,
        ];
    }

    /**
     * Create a new file
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $path = $request->get('path', '');

        $path = $this->getFullPath($path);

        return response([
            'success' => $this->fileservice->create($path),
        ], 201);
    }

    /**
     * Update a file
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $path     = $request->get('path', '');

        $path     = $this->getFullPath($path);

        $contents = $request->get('contents', '');

        return [
            'success' => $this->fileservice->update($path, $contents),
        ];
    }

    /**
     * Delete a file
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $path = $request->input('path', null);

        return [
            'success' => $this->fileservice->delete($path),
        ];
    }
}
