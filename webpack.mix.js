let mix = require('laravel-mix');
let fs = require('fs');
let path = require('path');
var readdirp = require('readdirp');
/*
 |Config webpack
*/

mix.webpackConfig(webpack  => {
   return {
      plugins: [
         new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
         })
      ]
   };
});
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
var concatStr = function(arr){
   return arr.join('/');
}

var assetsDir = concatStr(['resources','assets', 'js']);
var publicDir = concatStr(['public', 'js']);

var settings = {
    root: assetsDir,
    // entryType: 'all',
    fileFilter: '*.js'
};

readdirp(settings).on('data', function(file){
   if(['app.js', 'bootstrap.js'].indexOf(file.name) === -1 && !file.parentDir.includes('lib')){

      var replacePath = file.parentDir.indexOf('\\') !== -1 ? file.parentDir.replace('\\', '/') : file.parentDir;

      /*Console*/
      console.log('asset', assetsDir + '/' + replacePath + '/' + file.name);
      console.log('public',publicDir + '/' + replacePath + '/' + file.name);
      console.log('--------------------------------------------------------');
      /*End console*/

      mix.babel([assetsDir + '/' + replacePath + '/' + file.name], publicDir + '/' + replacePath + '/' + file.name);
   }
})
.on('warn', function(warn) {
   console.warn('warn : ', warn);
})
.on('error', function(error) {
   console.error(error);
})
.on('end', function() {
   console.log('finished');
});

mix.js('resources/assets/js/app.js', 'public/js')
   .copy('resources/assets/js/lib/*.js', 'public/js/lib')
   .copy('resources/assets/js/lib/localization/*.js', 'public/js/lib/localization')

   .sass('resources/assets/sass/app.scss', 'public/css')
   .copy('resources/assets/sass/lib/*.css', 'public/css/lib');
mix.version();
