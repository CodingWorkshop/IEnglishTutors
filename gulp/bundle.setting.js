module.exports = function (config) {
	var script = config.script;
	var style = config.style;
	var lib = config.lib;
	
	var css = {
		app: {
			home_lobby: style + 'app/Home/lobby.css'
		},
		webportal: {
			index: style + 'WebPortal/index.css',
			login: style + 'WebPortal/login.css',
			mailserversetting: style + 'WebPortal/mailserversetting.css',
			viewresources: style + 'WebPortal/viewresources.css'
		},
		bower: {
			bootstrap: lib + 'bootstrap/css/bootstrap.css',
			loadingbar: lib + 'angular-loading-bar/loading-bar.css',
			fontawesome: lib + 'font-awesome/css/font-awesome.css'
		}
	};
	
	var js = {
		apis: {
			core: script + 'apis/apis.core.js',
			system: script + 'apis/system.js',
			members: script + 'apis/members.js',
			asset: script + 'apis/asset.js'
		},
		app: {
			home_lobby: script + 'app/Home/lobby.js'
		},
		lab: {
			lab_download_form: script + 'app/Lab/download_form.js'
		},
		webportal: {
			index: script + 'WebPortal/index.js',
			login: script + 'WebPortal/login.js',
			mailserversetting: script + 'WebPortal/mailserversetting.js',
			viewresources: script + 'WebPortal/viewresources.js'
		},
		bower: {
			jquery: lib + 'jquery/jquery.js',
			bootstrap: lib + 'bootstrap/js/bootstrap.js',
			angular: lib + 'angular/angular.js',
			loadingbar: lib + 'angular-loading-bar/loading-bar.js'
		}
	};
	
	var setting = {
		"global": {
			"style": [
					css.bower.bootstrap,
					css.bower.loadingbar,
					css.bower.fontawesome
			],
			"script": [
					js.bower.jquery,
					js.bower.bootstrap,
					js.bower.angular,
					js.bower.loadingbar,
					js.apis.core
			]
		},
		"home.lobby": {
			"style": [
				css.app.home_lobby
			],
			"script": [
				js.app.home_lobby
			]
		},
		"webportal.index": {
			"style": [
				css.webportal.index
			],
			"script": [
				js.apis.members,
				js.apis.asset,
				js.apis.system,
				js.webportal.index
			]
		},
		"webportal.login": {
			"style": [
				css.webportal.login
			],
			"script": [
				js.apis.members,
				js.webportal.login
			]
		},
		"webportal.mailserversetting": {
			"style": [
				css.webportal.mailserversetting
			],
			"script": [
				js.apis.system,
				js.webportal.mailserversetting
			]
		},
		"webportal.viewresources": {
			"style": [
				css.webportal.viewresources
			],
			"script": [
				js.apis.system,
				js.webportal.viewresources
			]
		},
		"Lab.download_form": {
			"style": [

			],
			"script": [
				js.app.lab_download_form
			]
		},
	};
	
	return setting;
};