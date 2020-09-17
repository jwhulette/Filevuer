# Filevuer

<!-- [![Latest Version on Packagist][ico-version]][link-packagist] -->

[![Software License][ico-license]](LICENSE.md)

<!-- [![Build Status][ico-travis]][link-travis] -->
<!-- [![Coverage Status][ico-scrutinizer]][link-scrutinizer] -->
<!-- [![Quality Score][ico-code-quality]][link-code-quality] -->
<!-- [![Total Downloads][ico-downloads]][link-downloads] -->

This is a simple FTP/S3 filebrowser Laravel package based on the work done by [OFFLINE-GmbH/Online-FTP-S3](https://github.com/OFFLINE-GmbH/Online-FTP-S3)

-   ## Notable Changes

    -   Zip files are streamed from the resource instead of downloading to the server compressing and then serving the file.
    -   Uploaded zip files are opened on the server and then files streamed to the resource
    -   After upload, files are immediately deleted

-   The connections are defined in the config/filevuer.php file.
-   Asset files are copied to /public/vendor/filevuer.
-   The index file is copied to views/vendor/filevuer.
-   You can use restrict access to the route by placing

```PHP
Route::group(['middleware' => 'auth'], function () {
    jwhulette\filevuer\Filevuer::routes();
});
```

in your routes/web.php file

-   This is my first Laravel plugin so I'm sure I have make some mistakes. Please let me know if you come across any issues.

## Installation

Install using composer

```bash
composer require jwhulette/filevuer
```

Publish the assets and configuration

```PHP
php artisan vendor:publish --tag=filevuer
```

Laravel 5.5+:

-   If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

```PHP
Jwhulette\Filevuer\FilevuerServiceProvider::class
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email jwhulette@gmail.com instead of using the issue tracker.

## Credits

-   [Wes Hulette](https://github.com/jwhulette)

-   [OFFLINE GmbH](https://github.com/OFFLINE-GmbH/Online-FTP-S3)

<!-- - [All Contributors][link-contributors] -->

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/:vendor/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/:vendor/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/:vendor/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/:vendor/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/:vendor/:package_name.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/:vendor/:package_name
[link-travis]: https://travis-ci.org/:vendor/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/:vendor/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/:vendor/:package_name
[link-downloads]: https://packagist.org/packages/:vendor/:package_name
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
