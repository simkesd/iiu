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
        Sensor.get({}, function(response) {
            $scope.sensors = response.sensors;
            console.log(response.sensors);
        });
    }]);

imarControllers.controller('actuatorListCtrl', ['$scope', '$http',
    function ($scope, $http) {
        console.log('actuator controller called');
    }]);


imarControllers.controller('sensorAddCtrl', ['$scope', '$http', 'Sensor',
    function ($scope, $http, Sensor) {
        console.log('sensor add controller called');
        $scope.sensor = {};
        $scope.master = {};

        $scope.update = function(sensor) {
            $scope.master = angular.copy(sensor);
            Sensor.save(sensor);
        };



    }]);

imarControllers.controller('sensorSingleCtrl', ['$scope', '$routeParams', 'Sensor',
    function ($scope, $routeParams, Sensor) {
        console.log('sensor add controller called');


        Sensor.get({id: $routeParams.id}, function(response) {
            $scope.sensor = response.sensor[0];
        });
    }]);