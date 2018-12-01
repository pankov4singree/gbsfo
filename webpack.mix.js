let mix = require('laravel-mix');

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

mix.js('resources/assets/js/backend/require.js', 'public/backend/js/library.js')
    .scripts([
        'resources/assets/js/backend/admin-application.js',
        'resources/assets/js/backend/controllers.js',
        'resources/assets/js/backend/directives.js',
        'resources/assets/js/factories.js'
    ],'public/backend/js/app.js')
    .js('resources/assets/js/frontend/require.js', 'public/frontend/js/app.js')
    .sass('resources/assets/sass/backend/app.scss', 'public/backend/css')
    .sass('resources/assets/sass/frontend/app.scss', 'public/frontend/css');
