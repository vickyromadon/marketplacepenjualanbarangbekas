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

mix.sass('resources/assets/sass/bootstrap.scss', 'public/css');

mix
	.sass('resources/assets/sass/auth.scss', 'public/css')
	.js('resources/assets/js/auth.js', 'public/js');

mix
	.sass('resources/assets/sass/app.scss', 'public/css')
	.copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.min.js')
	.copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js/bootstrap.min.js')
	.copy('node_modules/fastclick/lib/fastclick.js', 'public/js/fastclick.js')
	.copy('node_modules/jquery-slimscroll/jquery.slimscroll.min.js', 'public/js/jquery.slimscroll.min.js')
	.copy('node_modules/icheck/icheck.min.js', 'public/js/icheck.min.js')
	.copy('node_modules/admin-lte/dist/js/adminlte.min.js', 'public/js/adminlte.min.js')
	.copy('node_modules/jquery-toast-plugin/dist/jquery.toast.min.js', 'public/js/jquery.toast.min.js')
	.copy('node_modules/datatables.net/js/jquery.dataTables.js', 'public/js/jquery.dataTables.js')
	.copy('node_modules/datatables.net-bs/js/dataTables.bootstrap.js', 'public/js/dataTables.bootstrap.js')
	.copy('node_modules/moment/moment.js', 'public/js/moment.js')
	.copy('node_modules/summernote/dist/summernote.js', 'public/js/summernote.js')
	.copy('node_modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js', 'public/js/bootstrap-timepicker.min.js')
	.copy('node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js', 'public/js/bootstrap-datepicker.min.js')
	.copy('node_modules/bootstrap-daterangepicker/daterangepicker.js', 'public/js/daterangepicker.js')
	.copy('node_modules/dropzone/dist/min/dropzone.min.js', 'public/js/dropzone.min.js')
	.js('resources/assets/js/app.js', 'public/js/app.js');

if (mix.inProduction() || process.env.npm_lifecycle_event !== 'hot') {
    mix.version();
}
