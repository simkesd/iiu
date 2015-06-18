var imarControllers = angular.module('imarControllers', []);

imarControllers.controller('sensorListCtrl', ['$scope', '$http', 'Sensor',
    function ($scope, $http, Sensor) {
        console.log('sensor controller called');
        Sensor.get({}, function (response) {
            $scope.sensors = response.sensors;
            console.log(response.sensors);
        });
    }]);

imarControllers.controller('actuatorListCtrl', ['$scope', '$http', 'Actuator',
    function ($scope, $http, Actuator) {
        Actuator.get({}, function (response) {
            $scope.actuators = response.actuators;
            console.log(response.actuators);
        });
    }]);


imarControllers.controller('sensorAddCtrl', ['$scope', '$http', 'Sensor',
    function ($scope, $http, Sensor) {
        console.log('sensor add controller called');
        $scope.sensor = {};
        $scope.master = {};

        $scope.update = function (sensor) {
            $scope.master = angular.copy(sensor);
            Sensor.save(sensor);
        };
    }]);

imarControllers.controller('sensorSingleCtrl', ['$scope', '$routeParams', 'Sensor',
    function ($scope, $routeParams, Sensor) {
        console.log('sensor add controller called');


        Sensor.get({id: $routeParams.id}, function (response) {
            $scope.sensor = response.sensor[0];
        });
    }]);

imarControllers.controller('actuatorAddCtrl', ['$scope', '$http', 'Actuator',
    function ($scope, $http, Actuator) {
        console.log('actuator add controller called');
        $scope.actuator = {};
        $scope.master = {};

        $scope.update = function (actuator) {
            $scope.master = angular.copy(actuator);
            Actuator.save(actuator);
        };
    }]);

imarControllers.controller('actuatorSingleCtrl', ['$scope', '$routeParams', 'Actuator',
    function ($scope, $routeParams, Actuator) {
        console.log('sensor add controller called');
        Actuator.get({id: $routeParams.id}, function (response) {
            $scope.actuator = response.actuator[0];
        });
    }]);
