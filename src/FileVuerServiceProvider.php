<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer;

use Spatie\LaravelPackageTools\Package;
use Jwhulette\Filevuer\Services\FileService;
use Jwhulette\Filevuer\Services\UploadService;
use Jwhulette\Filevuer\middleware\SessionDriver;
use Jwhulette\Filevuer\Services\DownloadService;
use Jwhulette\Filevuer\Services\DirectoryService;
use Jwhulette\Filevuer\Services\ConnectionService;
use Jwhulette\Filevuer\Services\ConfigurationService;
use Jwhulette\Filevuer\Services\FileServiceInterface;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Jwhulette\Filevuer\Services\UploadServiceInterface;
use Jwhulette\Filevuer\Services\DownloadServiceInterface;
use Jwhulette\Filevuer\Services\DirectoryServiceInterface;
use Jwhulette\Filevuer\Services\ConnectionServiceInterface;
use Jwhulette\Filevuer\Services\ConfigurationServiceInterface;

class FilevuerServiceProvider extends PackageServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Configure the package
     *
     * @param \Spatie\LaravelPackageTools\Package $package
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filevuer')
            ->hasConfigFile()
            ->hasViews()
            ->hasAssets()
            ->hasRoutes('web');
    }

    /**
     * Register services.
     */
    public function registeringPackage()
    {
        $this->app['router']->aliasMiddleware('sessionDriver', SessionDriver::class);

        $this->app->bind(ConfigurationServiceInterface::class, ConfigurationService::class);

        $this->app->bind(ConnectionServiceInterface::class, ConnectionService::class);

        $this->app->bind(DirectoryServiceInterface::class, DirectoryService::class);

        $this->app->bind(FileServiceInterface::class, FileService::class);

        $this->app->bind(DownloadServiceInterface::class, DownloadService::class);

        $this->app->bind(UploadServiceInterface::class, UploadService::class);
    }
}
