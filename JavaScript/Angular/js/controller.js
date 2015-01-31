var artistControllers = angular.module('artistControllers', ['ngAnimate']); // name spacing

// add in the annotation ['$scope', '$http', function ()] to prevent 
// any issues when you minify your JS code
// without the anotation minification will cause your Angular code to fail.

artistControllers.controller('ListController', ['$scope', '$http', function($scope, $http) {
	// $scope is the super variable used to pass information 
	// back and forth between the view and the model	
	
	// An angular Service is no more than a small bit of code that takes care of common tasks
	// $http is one such service.  This tackles communiication from the server and your application.
	
	$http.get('./js/data.json')
		 .success( function(data) {
			$scope.artists = data;
			$scope.artistOrder = 'name'; // used to make the default selection in the drop down menu.
		 });
	
}]);

// this controller accepts parameters from the router
artistControllers.controller('DetailsController', ['$scope', '$http', '$routeParams', 
	function($scope, $http, $routeParams) {
		$http.get('./js/data.json')
		 .success( function(data) {
			$scope.artists = data;
			$scope.whichItem = $routeParams.itemId;
			
			if ($routeParams.itemId > 0) {
				// need to change the var type to number since Angular returns the data as a string.
				// if not the first item in the array, then subtract 1
				$scope.prevItem = Number($routeParams.itemId) - 1;
			} else {
				// if you are on the first index, pressing the button will cause you to go to the end of the array instead.
				$scope.prevItem = $scope.artists.length - 1;
			}
			
			if ($routeParams.itemId < $scope.artists.length - 1) {
				// if you are not on the last item, the next one is just plus 1
				$scope.nextItem = Number($routeParams.itemId) + 1;
			} else {
				// if you are on the last item, then you will need to make the next one equal to the start of the area.
				$scope.nextItem = 0;
			}			
			
			
		 });
	
}]);
