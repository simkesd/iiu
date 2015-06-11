var imarControllers = angular.module('imarControllers', []);

imarControllers.controller('apiCtrl', ['$scope', '$http',
    function ($scope, $http) {
        //$http.get('http://imar.local/api/v1.0/sensor').success(function(data) {
        //    $scope.phones = data;
        //    console.log(data)
        //});

        //$scope.orderProp = 'age';
    }]);

imarControllers.controller('sensorListCtrl', ['$scope', '$http', 'Sensor',
    function ($scope, $http, Sensor) {
        console.log('sensor controller called');
        $scope.sensors = Sensor.query();
        //$scope.orderProp = 'age';
    }]);

imarControllers.controller('actuatorListCtrl', ['$scope', '$http',
    function ($scope, $http) {
        console.log('actuator controller called');
    }]);