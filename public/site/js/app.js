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
                controller: 'sensorListCtrl',
                activeTab: 'sensor-list'
            }).
            when('/sensor-add', {
                templateUrl: 'partials/sensor-add.html',
                controller: 'sensorAddCtrl',
                activeTab: 'sensor-add'
            }).
            when('/sensor/:id', {
                templateUrl: 'partials/sensor.html',
                controller: 'sensorSingleCtrl'
            }).
            when('/sensor/:id/values', {
                templateUrl: 'partials/sensor-values.html',
                controller: 'sensorSingleValuesCtrl',
                activeTab: 'sensor-values'
            }).
            when('/sensor/:id/value-add', {
                templateUrl: 'partials/sensor-value-add.html',
                controller: 'sensorValueAddCtrl',
                activeTab: 'sensor-value-add'
            }).
            when('/actuator', {
                templateUrl: 'partials/actuator-list.html',
                controller: 'actuatorListCtrl',
                activeTab: 'actuator-list'
            }).
            when('/actuator-add', {
                templateUrl: 'partials/actuator-add.html',
                controller: 'actuatorAddCtrl',
                activeTab: 'actuator-add'
            }).
            when('/actuator/:id', {
                templateUrl: 'partials/actuator.html',
                controller: 'actuatorSingleCtrl'
            }).
            when('/actuator/:id/values', {
                templateUrl: 'partials/actuator-values.html',
                controller: 'actuatorSingleValuesCtrl',
                activeTab: 'actuator-values'
            }).
            when('/', {
                templateUrl: 'partials/welcome.html',
                controller: 'welcomeCtrl',
                activeTab: 'welcome'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);

imarApp.run(['$http', function($http) {
    $http.defaults.headers.common.Authorization = 'Basic ' + window.btoa(unescape(encodeURIComponent('firstuser:first_password')))
}]);

