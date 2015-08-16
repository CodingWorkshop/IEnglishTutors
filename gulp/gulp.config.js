module.exports = function (fs) {
	
	var paths = {
		assets: __base + 'assets/',
		bower: __base + 'bower_components/'
	};
	
	var config = {
		bundleSettingPath: __base + 'gulp/bundle.setting',
		bower: {
		"bootstrap": paths.bower + 'bootstrap/dist/**/*.{js,map,css,ttf,svg,woff,eot}',
		"jquery": paths.bower + 'jquery/dist/jquery*.{js,map}'
		},
		lib: paths.assets + 'lib/',
		js: paths.assets + 'js/',
		css: paths.assets + 'css/',
		script: paths.assets + 'Script/',
		style: paths.assets + 'Style/',
		fonts: {
			path: paths.assets + 'fonts/',
			source: [
				paths.bower + 'bootstrap/dist/fonts/*.{ttf,svg,woff,eot}'
			]
		}
	};
	
	return config;
}