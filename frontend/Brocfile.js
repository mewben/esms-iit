/* global require, module */

var EmberApp = require('ember-cli/lib/broccoli/ember-app');

var app = new EmberApp({
	fingerprint: {
		enabled: false
	}
});

// Use `app.import` to add additional libraries to the generated
// output files.
//
// If you need to use different assets in different
// environments, specify an object as the first parameter. That
// object's keys should be the environment name and the values
// should be the asset to use in that environment.
//
// If the library that you are including contains AMD or ES6
// modules that you would like to import into your application
// please specify an object with the list of modules as keys
// along with the exports of each module as its value.

// bootstrap
// TODO : minimize usage bootstrap
app.import('vendor/bootstrap/dist/js/bootstrap.min.js');

// font-awesome fonts
app.import('vendor/font-awesome/fonts/fontawesome-webfont.eot');
app.import('vendor/font-awesome/fonts/fontawesome-webfont.svg');
app.import('vendor/font-awesome/fonts/fontawesome-webfont.ttf');
app.import('vendor/font-awesome/fonts/fontawesome-webfont.woff');
app.import('vendor/font-awesome/fonts/FontAwesome.otf');

// selectize
app.import('vendor/selectize/dist/js/standalone/selectize.min.js');

// accounting
app.import('vendor/accounting/accounting.min.js');
app.import('vendor/moment/min/moment.min.js');

// csv parse
app.import('vendor/papa-parse/jquery.parse.min.js');

// toastr
app.import('vendor/toastr/toastr.min.js');

// custom
app.import('vendor/custom/excelexport/jquery.battatech.excelexport.min.js');
app.import('vendor/custom/script.js');


module.exports = app.toTree();
