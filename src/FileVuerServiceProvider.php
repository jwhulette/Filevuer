<?php

declare(strict_types=1);

namespace Jwhulette\Filevuer;

use Spatie\LaravelPackageTools\Package;
use jwhulette\filevuer\services\FileService;
use jwhulette\filevuer\services\UploadService;
use jwhulette\filevuer\middleware\SessionDriver;
use jwhulette\filevuer\services\DownloadService;
use jwhulette\filevuer\services\DirectoryService;
use jwhulette\filevuer\services\ConnectionService;
use jwhulette\filevuer\services\ConfigurationService;
use jwhulette\filevuer\services\FileServiceInterface;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use jwhulette\filevuer\services\UploadServiceInterface;
use jwhulette\filevuer\services\DownloadServiceInterface;
use jwhulette\filevuer\services\DirectoryServiceInterface;
use jwhulette\filevuer\services\ConnectionServiceInterface;
use jwhulette\filevuer\services\ConfigurationServiceInterface;

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
