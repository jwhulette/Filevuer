const mix = require('laravel-mix');

mix.setPublicPath("../resources/dist");

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

mix.js("resources/js/app.js", "js/filevuer.js")
    .vue()
    .sass("resources/sass/app.scss", "css/filevuer.css")
    .version()
    .sourceMaps();
