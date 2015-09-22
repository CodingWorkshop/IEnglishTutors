(function () {
	angular.module('apis')
		.factory('agendaApi', agendaApi);

	agendaApi.$inject = ['$http', '$window', 'Upload'];
	function agendaApi($http, $window, $upload) {
		var _Site = $window['$base_url'];
		var _Add = _Site + 'Services/Agenda/Add';
		var _Upload = _Site + 'Services/Agenda/Upload';
		
		return {
			Add: function(options) {
				return $http({
					method: 'POST',
					url: _Add,
					data: options.data
				}).then(function (response) {
					options.success(response.data);
				}, function (response) {
					options.error(response.data);
				});
			},
			Upload: function (options) {
				return $upload.upload({
					url: _Upload,
					fields: options.options,
					file: options.file
				}).progress(function (evt) {
					options.progress(evt);
				}).success(function (data, status, headers, config) {
					options.success(data, status, headers, config);
				}).error(function (data, status, headers, config) {
					options.error(data, status, headers, config)
				});
			}
		};
	}
})();