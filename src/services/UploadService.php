<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer\Services;

use Exception;
use ZipArchive;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Jwhulette\Filevuer\Services\SessionService;

class UploadService implements UploadServiceInterface
{
    /**
     * Save the uploaded files
     *
     * @param string $path
     * @param array<UploadedFile> $files
     * @param bool $extract
     *
     * @return void
     * @throws Exception
     */
    public function uploadFiles(string $path, array $files, bool $extract = false): void
    {
        foreach ($files as $file) {
            if ($extract === \false) {
                $this->uploadFile($path, $file);
            } else {
                // Extract zip after upload
                if ($file->getClientOriginalExtension() == 'zip') {
                    $this->unzipArchive($path, $file);
                }
            }
        }
    }

    /**
     * @param string $path
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return void
     * @throws Exception
     */
    protected function unzipArchive(string $path, UploadedFile $file): void
    {
        $zipArchive  = new \ZipArchive();

        $resource = $zipArchive->open($file->getRealPath());

        if (!$resource) {
            File::delete($file->getRealPath());

            throw new Exception('Failed to extract zip archive.');
        }


        $fileCount = $zipArchive->numFiles;

        $this->createDirectories($zipArchive, $fileCount, $path);

        for ($i = 0; $i < $fileCount; $i++) {
            $filename   = $zipArchive->getNameIndex($i);

            $filestream = $zipArchive->getStream($filename);

            if (!$filestream) {
                File::delete($file->getRealPath());

                throw new Exception('Failed to get zipped file - ' . $filename);
            }

            // If a file, upload the file
            if (File::isFile($filename)) {
                $uploadPath = sprintf('%s/%s', $path, $filename);

                $response = Storage::disk(SessionService::getConnectionName())
                    ->put($uploadPath, $filestream);

                if (!$response) {
                    File::delete($file->getRealPath());

                    throw new Exception("Error creating file on server");
                }
            }
        }

        try {
            $zipArchive->close();
        } catch (\Throwable $th) {
            throw new Exception("Error creating file on server " . $th->getMessage());
        }

        File::delete($file->getRealPath());
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
            $filename = $zipArchive->getNameIndex($i);

            $this->createDirectory($path, $filename);
        }
    }

    /**
     * Create a directory
     *
     * @param string $path
     * @param string $filename
     */
    protected function createDirectory(string $path, string $filename): void
    {
        $directory = File::dirname($filename);

        if ($directory !== '.') {
            $directoryPath =  sprintf('%s/%s', $path, $directory);

            Storage::disk(SessionService::getConnectionName())->makeDirectory($directoryPath);
        }
    }

    /**
     * Uplad the file
     *
     * @param string $path
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return void
     * @throws Exception
     */
    protected function uploadFile(string $path, UploadedFile $file): void
    {
        $uploadPath = sprintf('%s/%s', $path, $file->getClientOriginalName());

        $response = Storage::disk(SessionService::getConnectionName())
            ->put($uploadPath, File::get($file->getRealPath()));

        if (!$response) {
            File::delete($file->getRealPath());

            throw new Exception("Error uploading file", 1);
        }
    }
}
