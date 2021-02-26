<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Exception;
use ZipArchive;
use RuntimeException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Traits\SessionDriverTrait;

class UploadService implements UploadServiceInterface
{
    use SessionDriverTrait;

    /**
     * Process the uploaded files
     *
     * @param string $path
     * @param array $files
     * @param bool|null $extract
     *
     * @return void
     *
     * @throws Exception
     */
    public function uploadFiles(string $path, array $files, ?bool $extract = false): void
    {
        foreach ($files as $file) {
            if (!$extract) {
                $this->uploadFile($path, $file);
            } else {
                if ($file->getClientOriginalExtension() == 'zip') {
                    $this->unzipArchive($path, $file);
                } else {
                    $this->uploadFile($path, $file);
                }
            }
        }
    }

    /**
     * @param string $path
     * @param UploadedFile $file
     *
     * @return void
     *
     * @throws RuntimeException
     */
    protected function unzipArchive(string $path, UploadedFile $file): void
    {
        $zipArchive  = new \ZipArchive();

        $resource = $zipArchive->open($file->getRealPath());

        if ($resource === true) {
            $fileCount = $zipArchive->numFiles;

            $this->createDirectories($zipArchive, $fileCount, $path);

            for ($i = 0; $i < $fileCount; $i++) {
                $filename   = $zipArchive->getNameIndex($i);

                $filestream = $zipArchive->getStream($filename);

                if (!$filestream) {
                    File::delete($file->getRealPath());

                    throw new \RuntimeException('Failed to get zipped file - ' . $filename);
                }

                if (pathinfo($filename, PATHINFO_EXTENSION)) {
                    $uploadPath = $this->getUploadPath($path, $filename);

                    $response   = Storage::disk(SessionService::getConnectionName())
                        ->put($uploadPath, $filestream);

                    if (!$response) {
                        unlink($file->getRealPath());

                        throw new \RuntimeException("Error creating file on server");
                    }
                }
            }

            $zipArchive->close();

            File::delete($file->getRealPath());
        } else {
            File::delete($file->getRealPath());

            throw new \RuntimeException('Failed to extract zip archive.');
        }
    }

    /**
     * Create the directories before attampting to copy the files
     *
     * @param ZipArchive $zipArchive
     * @param int $fileCount
     * @param string $path
     *
     * @return void
     */
    protected function createDirectories(ZipArchive $zipArchive, int $fileCount, string $path): void
    {
        for ($i = 0; $i < $fileCount; $i++) {
            $filename   = $zipArchive->getNameIndex($i);

            $this->createDirectory($path, $filename);
        }
    }

    /**
     * Crate a directory
     *
     * @param string $filename
     */
    protected function createDirectory(string $path, string $filename): void
    {
        $directory = dirname($filename);

        if ('.' !== $directory) {
            $directoryPath = $this->getUploadPath($path, $directory) . '/';

            Storage::disk(SessionService::getConnectionName())->makeDirectory($directoryPath);
        }
    }

    /**
     * Uplad the file
     *
     * @param string $path
     * @param UploadedFile $file
     *
     * @return void
     *
     * @throws Exception
     */
    protected function uploadFile(string $path, UploadedFile $file): void
    {
        $uploadPath = $this->getUploadPath($path, $file->getClientOriginalName());

        $response   = Storage::disk(SessionService::getConnectionName())
            ->put($uploadPath, file_get_contents($file->getRealPath()));

        File::delete($file->getRealPath());

        if (!$response) {
            throw new Exception("Error uploading file", 1);
        }
    }

    /**
     * Get the path to upload the file
     *
     * @param string $path
     * @param string $filename
     *
     * @return string
     */
    public function getUploadPath(string $path, string $filename): string
    {
        $path =  ltrim($path, '/');

        return $this->getFullPath($path . $filename);
    }
}
