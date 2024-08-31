const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/echo.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css')
   .version();

   // webpack.mix.js


mix.js('resources/js/custom-editor/editor.js', 'public/js/custom-editor')
    .postCss('resources/css/custom-editor/editor.css', 'public/css/custom-editor', [
        //
    ])
    .version(); // Enables cache busting
