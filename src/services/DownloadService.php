<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Carbon\Carbon;
use ZipStream\ZipStream;
use Illuminate\Support\Collection;
use Jwhulette\Filevuer\Services\SessionService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Jwhulette\Filevuer\Services\DownloadServiceInterface;

/**
 * Download Service Class
 */
class DownloadService implements DownloadServiceInterface
{
    /**
     * @param array $paths
     *
     * @return string
     */
    public function setHash(array $paths): string
    {
        $hash = Carbon::now()->getTimestamp();

        session()->put(SessionService::FILEVUER_DOWNLOAD . $hash, $paths);

        return (string) $hash;
    }

    /**
     * @param string $hash
     *
     * @return Collection
     */
    protected function getHash(string $hash): Collection
    {
        $paths = session(SessionService::FILEVUER_DOWNLOAD . $hash);

        session()->forget(SessionService::FILEVUER_DOWNLOAD . $hash);

        return collect($paths);
    }

    /**
     * @param string $hash
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getDownload(string $hash): StreamedResponse
    {
        $downloads = $this->getHash($hash);

        if ($downloads->count() == 1 && $downloads->pluck('type')->first() == 'file') {
            return $this->downloadSingleFile($downloads->first());
        }

        return $this->downloadZipFile($downloads);
    }

    /**
     * @param Collection $downloads
     *
     * @return StreamedResponse
     */
    protected function downloadZipFile(Collection $downloads): StreamedResponse
    {
        $zipFilename = $this->getZipFilename();

        $zipStream = new ZipStream($zipFilename);

        return response()->stream(function () use ($zipStream, $downloads) {
            $downloads->each(function ($downloadFile) use ($zipStream) {
                $this->addFilesToZip($downloadFile, $zipStream);
            });

            $zipStream->finish();
        }, 200, [
            "Content-Type" => 'application/octet-stream;',
            'Content-Disposition' => 'attachment; filename=' . $zipFilename,
        ]);
    }

    /**
     * @param array $file
     * @param ZipStream $zipStream
     *
     * @return void
     */
    protected function addFilesToZip(array $file, ZipStream $zipStream): void
    {
        if ($file['type'] == 'dir') {
            $listing = Storage::disk(SessionService::getConnectionName())->allDirectories($file['path']);

            foreach ($listing as $item) {
                $this->addFileToZip($zipStream, $item, $file['dirname']);
            }
        }

        $this->addFileToZip($zipStream, $file, $file['dirname']);
    }

    /**
     * @param ZipStream $zipStream
     * @param array $file
     * @param string|null $rootDir
     *
     * @return void
     */
    protected function addFileToZip(ZipStream $zipStream, array $file, ?string $rootDir = null): void
    {
        if ($file['type'] == 'dir') {
            return;
        }

        $filePath = substr($file['path'], strlen($rootDir) + 1);

        $stream   = Storage::disk(SessionService::getConnectionName())->readStream($file['path']);

        $zipStream->addFileFromStream($filePath, $stream);
    }

    /**
     * @return string
     */
    protected function getZipFilename(): string
    {
        $connectionName = session(SessionService::FILEVUER_CONNECTION_NAME);

        return strtolower($connectionName) . '_' . Carbon::now()->getTimestamp() . '.zip';
    }

    /**
     * @param array $downloadFile
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function downloadSingleFile(array $downloadFile): StreamedResponse
    {
        return response()->stream(function () use ($downloadFile) {
            $stream = Storage::disk(SessionService::getConnectionName())->readStream($downloadFile['path']);
            fpassthru($stream);
        }, 200, [
            "Content-Type" => 'application/octet-stream;',
            'Content-Disposition' => 'attachment; filename="' . $downloadFile['basename'] . '"',
        ]);
    }
}
