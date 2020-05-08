const mix = require('laravel-mix');

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

// mix.autoload({
//     jquery: ['$', 'window.jQuery',"jQuery","window.$","jquery","window.jquery"]
// })
mix.styles([
    'public/css/style.css',
    // 'public/css/bootstrap.css',
    'public/css/custom-styles.css',
    // 'public/css/font-awesome.css',
    'public/css/argon.min.css',
    'public/fontawesome/css/all.css',

], 'public/css/all.css')
.js([
    'public/js/script.js',
    // 'public/js/jquery-1.10.2.js',
    // 'public/js/bootstrap.min.js',
    'public/fontawesome/js/all.js',
    'resources/js/app.js',

], 'public/js/all.js')
.browserSync('http://5s-Portal.local');


// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');
