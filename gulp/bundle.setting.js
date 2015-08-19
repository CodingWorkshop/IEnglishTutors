module.exports = function (config) {
	var script = config.script;
	var style = config.style;
	var lib = config.lib;
	
	var css = {
		app: {
			css1: style + 'app/css1.css'
		},
		bower: {
			bootstrap: lib + 'bootstrap/css/bootstrap.css',
			loadingbar: lib + 'loadingbar/loading-bar.css'
		}
	};
	
	var js = {
		apis: {
			system: script + 'apis/system.js',
			members: script + 'apis/members.js',
			asset: script + 'apis/asset.js'
		},
		app: {
			js1: script + 'app/js1.js'
		},
		webportal: {
			index: script + 'WebPortal/index.js',
			login: script + 'WebPortal/login.js'
		},
		bower: {
			jquery: lib + 'jquery/jquery.js',
			bootstrap: lib + 'bootstrap/js/bootstrap.js',
			angular: lib + 'angular/angular.js',
			loadingbar: lib + 'loadingbar/loading-bar.js'
		}
	};
	
	var setting = {
		"global": {
			"style": [
					css.bower.bootstrap,
					css.bower.loadingbar
			],
			"script": [
					js.bower.jquery,
					js.bower.bootstrap,
					js.bower.angular,
					js.bower.loadingbar
			]
		},
		"home.lobby": {
			"style": [
				css.app.css1
			],
			"script": [
				js.app.js1
			]
		},
		"webportal.login": {
			"style": [
			],
			"script": [
				js.apis.members,
				js.webportal.login
			]
		},
		"webportal.index": {
			"style": [
			],
			"script": [
				js.apis.asset,
				js.webportal.index
			]
		}
	};
	
	return setting;
};