var myApp = angular.module('JrPreInterview', []);

myApp.controller('HourglassController', ['$scope', '$http', '$sce', function($scope, $http, $sce) {

			$scope.submit = function () {

					var dataObj = {
							height: $scope.height,
							percent: $scope.percent
					};
					$http.post('buildHourglass.php', dataObj)
							.then(function (result) {
									$scope.hourglass = $sce.trustAsHtml(result.data);
									$scope.height = "";
									$scope.percent = "";
							});
					}
	}]);