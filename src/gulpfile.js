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

elixir(function(mix) {

	// ---------- GENERAL STUFF AND WAXIS MODULES ---------- //

		// Copy general, common stuff to public
	    mix.copy('node_modules/font-awesome', 'public/assets/common/libs/font-awesome');
	    mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'public/assets/common/libs/bootstrap/bootstrap.min.js');
	    mix.copy('resources/assets/common/libs/ionicons', 'public/assets/common/libs/ionicons');
	    mix.copy('resources/assets/common/libs/jquery', 'public/assets/common/libs/jquery');
	    mix.copy('resources/assets/common/libs/jquery-ui', 'public/assets/common/libs/jquery-ui');
	    mix.copy('resources/assets/common/libs/form/libs/ckeditor', 'public/assets/common/libs/ckeditor');

	    // Repeater js 
	    mix.scripts([
	        '../common/libs/repeater/js/jquery.wax.repeater.js',
	        '../common/libs/repeater/js/repeater.js'
	    ], 'public/assets/common/libs/repeater/js/repeater.js');

	    // Form js
	    mix.scripts([
	        '../common/libs/form/js/form.js',
	        '../common/libs/form/js/jquery.waxis.form.js',
	        '../common/libs/form/js/jquery.waxis.counter.js',
	        '../common/libs/form/libs/form-validation-io/js/formValidation.popular.min.js',
	        '../common/libs/form/libs/form-validation-io/js/framework/bootstrap.min.js',
	        '../common/libs/form/libs/bootstrap-select/js/bootstrap-select.min.js',
	        '../common/libs/form/libs/bootstrap-switch/bootstrap-switch.min.js',
	        '../common/libs/form/libs/bootstrap-slider/bootstrap-slider.js',
	        '../common/libs/form/libs/bootstrap-tags/bootstrap-tagsinput.min.js',
	        '../common/libs/form/libs/typeahead/bloodhound.min.js',
	        '../common/libs/form/libs/typeahead/typeahead.jquery.min.js',
	        '../common/libs/form/libs/handlebars/handlebars-v3.0.3.js',
	        '../common/libs/form/libs/dropzone/dropzone.js',
	        '../common/libs/form/libs/serialize-object/jquery.serialize-object.min.js',
	        '../common/libs/form/libs/fancybox/jquery.fancybox.js',
	        '../common/libs/form/libs/autogrow/jquery.ns-autogrow.min.js',
	        '../common/libs/form/libs/format-money/format-money.js',
	        '../common/libs/form/libs/colorpicker/jqColorPicker.min.js',
	        '../common/libs/form/libs/bootstrap-datepicker/js/bootstrap-datepicker.js',
	        '../common/libs/form/js/validators.js',
	    ], 'public/assets/common/libs/form/js/form.js');

	    // Stat js
	    mix.scripts([
	        '../common/libs/stat/js/jquery.waxis.stat.js',
	    ], 'public/assets/common/libs/stat/js/stat.js');



	// ---------- ADMIN ---------- //

	    // (1) Copies
	    mix.copy('resources/assets/admin/svg', 'public/assets/admin/svg');
	    mix.copy('resources/assets/admin/favicon', 'public/assets/admin/favicon');
	    mix.copy('resources/assets/admin/images', 'public/assets/admin/images');
	    mix.copy('resources/assets/admin/css/editor', 'public/assets/admin/css/editor');

	    // (2) JS
	    mix.scripts([
	        '../admin/libs/pace/pace.min.js',
	        '../admin/libs/toastr/toastr.min.js',
	        '../admin/libs/perfect-scrollbar/js/min/perfect-scrollbar.jquery.min.js',
	        '../admin/libs/side-bar/js/classie.js',
	        '../admin/libs/side-bar/js/sidebar-effects.js',
	        '../admin/libs/metis-menu/jquery.metisMenu.js',
	        '../admin/js/cms.js',
	    ], 'public/assets/admin/js/app.js');

	    // (3) Create css from sass - this is the actual app style
	    mix.sass([
	        '../admin/sass/theme.scss'
	    ], 'resources/assets/admin/css/theme.css');

	    // (4) Combine app css with other plugin css files into one theme file
	    mix.styles([
	        '../admin/libs/pace/pace.css',
	        '../admin/libs/toastr/toastr.css',
	        '../admin/libs/perfect-scrollbar/css/perfect-scrollbar.css',
	        '../admin/libs/metis-menu/metisMenu.css',
	        '../admin/libs/side-bar/css/sidebar.css',
	        '../admin/css/theme.css'
	    ], 'public/assets/admin/css/theme.css');



	// ---------- APP ---------- //
	    
		// Extend if needed based on Admin section (1-4)

	    // (2)
	    mix.scripts([
	        '../app/js/app.js'
	    ],'public/assets/app/js/app.js');

		// (3)
	    mix.sass([
	        '../app/sass/theme.scss',
	    ],'resources/assets/app/css/theme.css');

		// (4)
	    mix.styles([
	        '../app/css/theme.css',
	    ],'public/assets/app/css/theme.css');



    // ---------- VERSIONING ---------- //

	    mix.version([
	        'public/assets/admin/js/app.js',
	        'public/assets/admin/css/theme.css',

	        'public/assets/app/js/app.js',
	        'public/assets/app/css/theme.css',
	    ]);
});

elixir.Task.find('sass').watch('./**/*.scss');