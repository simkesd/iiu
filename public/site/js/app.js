var imarApp= angular.module('imarApp', [
    'ngRoute',
    'imarControllers',
    'imarServices'
]);

imarApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/sensor', {
                templateUrl: 'partials/sensor-list.html',
                controller: 'sensorListCtrl'
            }).
            when('/actuator', {
                templateUrl: 'partials/actuator-list.html',
                controller: 'actuatorListCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);
