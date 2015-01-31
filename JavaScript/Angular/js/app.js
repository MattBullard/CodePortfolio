var myApp = angular.module('myApp', [
	'ngRoute', 				// this is a dependacy that turns on the deep linking for us
	'artistControllers'	 	// then we specify the JS that will handle this module
]);

// ['$routeProvider'] is a service like the $http service
myApp.config(['$routeProvider', function($routeProvider) {
	$routeProvider.
		when('/list', {
			templateUrl		: 'partials/list.html', // this matches the document in the partials folder
			controller		: 'ListController' // this is the name of the controller in the controller.js document
		}).
		// when someone clicks on the details link, they will get the details 
		// partial and it will pass the value of itemId along with it to the controller
		// via the $routeParams
		when('/details/:itemId', {
			templateUrl		: 'partials/details.html', 
			controller		: 'DetailsController' 
		}).
		otherwise({
			redirectTo: '/list' // the default route
		});
}]);
