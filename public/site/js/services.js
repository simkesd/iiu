var imarServices = angular.module('imarServices', ['ngResource']);

var root = 'http://imar.local';


imarServices.factory('Sensor', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/sensor/:id', {});
    }]);

imarServices.factory('SensorValues', ['$resource',
    function ($resource) {
        return $resource('/api/v1.0/sensor/:id/values', {});
    }]);

imarServices.factory('ActuatorValuesDaily', ['$resource',
    function ($resource) {
        return $resource('/api/v1.0/actuator/:id/timeLapseData', {});
    }]);


imarServices.factory('Actuator', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/actuator/:id', {});
    }]);

imarServices.factory('Util', ['$location', 'SensorValues', 'ActuatorValues', '$routeParams',
    function ($location, SensorValues, ActuatorValues, $routeParams) {
        var util = {
            redirect: function (path) {
                $location.path(path);
            },
            datesFromSensorValues: function (values) {
                var data = [];
                for (var i = 0; i < values.length; i++) {
                    // Split timestamp into [ Y, M, D, h, m, s ]
                    var t = values[i].created_at.split(/[- :]/);

                    // Apply each element to the Date function
                    var date = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
                    //console.log(date);
                    //var month = date.toLocaleString("en-US", { month: "long" });
                    //var day = date.getDay();
                    data.push({
                        period: date.getTime(),
                        value: parseInt(values[i].value)
                    })
                }

                return data;
            },
            handleDatePickerChangeEvent: function ($scope, type, from, to) {
                var fromDateTimeUrlEncoded = encodeURIComponent(from);
                var toDateTimeUrlEncoded = encodeURIComponent(to);

                if (type == 'sensor') {
                    SensorValues.get({id: $routeParams.id, from: fromDateTimeUrlEncoded, to: toDateTimeUrlEncoded}, function (response) {
                        $scope.sensorValues = response.sensorValues;
                        $scope.sensor = response.sensor;
                        $scope.sensor.latest_value = response.latest_value;
                        $scope.data = util.datesFromSensorValues($scope.sensorValues);

                        $scope.chart.setData($scope.data);
                    });
                    return true;
                } else if (type == 'actuator') {
                    ActuatorValues.get({id: $routeParams.id, from: fromDateTimeUrlEncoded, to: toDateTimeUrlEncoded}, function (response) {
                        $scope.actuatorValues = response.actuatorValues;
                        $scope.actuator = response.actuator;
                        $scope.actuator.latest_value = response.latest_value;
                        $scope.periodOn = response.periodOn;
                        $scope.periodOff = response.periodOff;
                        $scope.data = util.datesFromSensorValues($scope.actuatorValues);

                        console.log($scope.periodOn, $scope.periodOff);

                        $scope.chart.setData($scope.data);
                        $scope.donutChart.setData([
                            {label: "Minutes On", value: $scope.periodOn},
                            {label: "Minutes off", value: $scope.periodOff}
                        ]);

                    });
                    return true;
                } else {

                }
            }
        };
        return util;
    }]);

imarServices.factory('ActuatorValues', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/actuator/:id/values', {});
    }]);

imarServices.factory('ElectricityPrice', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/actuator/:id/calculatePrice', {});
    }]);