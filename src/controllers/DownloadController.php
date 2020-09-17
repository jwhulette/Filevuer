<?php
declare(strict_types = 1);

namespace Jwhulette\Filevuer\controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use jwhulette\filevuer\services\DownloadServiceInterface;

/**
 * DownloadController Class
 */
class DownloadController extends Controller
{
    protected DownloadServiceInterface $downloadService;

    /**
     *  __construct
     *
     * @param DownloadServiceInterface $downloadService
     */
    public function __construct(DownloadServiceInterface $downloadService)
    {
        $this->downloadService = $downloadService;
    }

    /**
     * Return an encrypted path request to send to the get response for download
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request): Response
    {
        $hash = $this->downloadService->setHash($request->path);
        
        return response($hash);
    }


    /**
     * Send the requested file(s) to download
     *
     * @param string $hash
     *
     * @return StreamedResponse
     */
    public function download($hash): StreamedResponse
    {
        return $this->downloadService->getDownload($hash);
    }
}
