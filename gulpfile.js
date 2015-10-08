var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var vendor_js = ['../../../vendor/components/jquery/jquery.min.js',
    '../../../vendor/twbs/bootstrap/dist/js/bootstrap.min.js'];
var vendor_css = ['../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css'];

elixir(function(mix) {
    mix.scripts(vendor_js, 'public/js/vendor.js')
        .styles(vendor_css, 'public/css/vendor.css')
        .sass('app.scss')
        .browserSync({proxy:'futbol.dev'});
});
