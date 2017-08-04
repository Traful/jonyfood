app
.controller("home", ["$scope", "$http", function($scope, $http) {
	$scope.msg = "";
	var req = {
		method: "POST",
		dataType: "json",
		url: "php/getcomercios.php",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded"
		}
	};
	$scope.comercios = null;
	$http(req).then(function(response) {
		$scope.comercios = response.data;
		console.log($scope.comercios);
	});
}]);