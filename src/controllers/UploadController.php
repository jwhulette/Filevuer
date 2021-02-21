<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jwhulette\Filevuer\Services\UploadServiceInterface;

/**
 * UploadController Class
 */
class UploadController extends Controller
{
    /**
     * @var UploadServiceInterface
     */
    protected $uploadService;

    /**
     * @param UploadServiceInterface $uploadService
     */
    public function __construct(UploadServiceInterface $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Upload a file or folder
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $files = $request->hasFile('files') ? $request->allFiles()['files'] : [];
        if (count($files) < 1) {
            return response()->json('No files received', 500);
        }

        try {
            $this->uploadService->uploadFiles($request->path, $request->allFiles()['files'], (bool) $request->extract);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('OK', 200);
    }
}
