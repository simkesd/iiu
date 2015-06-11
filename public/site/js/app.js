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
            when('/sensor-add', {
                templateUrl: 'partials/sensor-add.html',
                controller: 'sensorAddCtrl'
            }).
            when('/sensor/:id', {
                templateUrl: 'partials/sensor.html',
                controller: 'sensorSingleCtrl'
            }).
            when('/actuator', {
                templateUrl: 'partials/actuator-list.html',
                controller: 'actuatorListCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

imarApp.run(['$http', function($http) {
    $http.defaults.headers.common.Authorization = 'Basic ' + window.btoa(unescape(encodeURIComponent('firstuser:first_password')))
}]);

