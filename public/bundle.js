var path = require("path");
var uglifyJS = require("uglify-js");
var Builder = require('systemjs-builder');
var builder = new Builder('./', 'systemjs.config.js');
var files = [
    "node_modules/jquery/dist/jquery.min.js",
    "node_modules/materialize-css/dist/js/materialize.min.js",
    "node_modules/core-js/client/shim.min.js",
    "node_modules/zone.js/dist/zone.min.js",
    "node_modules/reflect-metadata/Reflect.js",
    "node_modules/systemjs/dist/system.js",
    "systemjs.config.js",
    "out/tmp.js"
];
var fs = require('fs');


builder
    .bundle('out', 'out/tmp.js', { minify: false, sourceMaps: true})
    .then(function() {
        


	var result = uglifyJS.minify(files, {
	    compress: {
		dead_code: true,
		global_defs: {
		    DEBUG: false
		}
	    }});
	process.stdout.write(result.code);
	

    })
    .catch(function(err) {
        console.log('Build error');
        console.log(err);
	process.exit(1);
    });


