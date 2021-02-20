const mix = require('laravel-mix');

mix.setPublicPath('./');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.extract(['vue']);

mix.js('resources/assets/js/app.js', 'public/js/filevuer.js')
    .vue()
    .sass('resources/assets/sass/app.scss', 'public/css/filevuer.css')
    .version();
