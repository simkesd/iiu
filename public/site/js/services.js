var imarServices = angular.module('imarServices', ['ngResource']);

imarServices.factory('Sensor', ['$resource',
    function($resource){
        return $resource('http://imar.local/api/v1.0/sensor/:id', {});
    }]);

imarServices.factory('SensorValues', ['$resource',
    function($resource){
        return $resource('http://imar.local/api/v1.0/sensor/:id/values', {});
    }]);

imarServices.factory('Actuator', ['$resource',
    function($resource){
        return $resource('http://imar.local/api/v1.0/actuator/:id', {});
    }]);

imarServices.factory('Util', ['$location', 'SensorValues', '$routeParams',
    function($location, SensorValues, $routeParams){
        var util = {
            redirect: function(path){
                $location.path(path);
            },
            datesFromSensorValues: function(sensorValues) {
                var data = [];
                for (var i = 0; i < sensorValues.length; i++) {
                    // Split timestamp into [ Y, M, D, h, m, s ]
                    var t = sensorValues[i].created_at.split(/[- :]/);

                    // Apply each element to the Date function
                    var date = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
                    //console.log(date);
                    //var month = date.toLocaleString("en-US", { month: "long" });
                    //var day = date.getDay();
                    data.push({
                        period: date.getTime(),
                        value: parseInt(sensorValues[i].value)
                    })
                }

                return data;
            },
            handleDatePickerChangeEvent: function($scope, from, to) {
                var fromDateTimeUrlEncoded = encodeURIComponent(from);
                var toDateTimeUrlEncoded = encodeURIComponent(to);

                SensorValues.get({id: $routeParams.id, from: fromDateTimeUrlEncoded, to: toDateTimeUrlEncoded}, function (response) {
                    $scope.sensorValues = response.sensorValues;
                    $scope.sensor = response.sensor;
                    $scope.sensor.latest_value = response.latest_value;
                    $scope.data = util.datesFromSensorValues($scope.sensorValues);

                    $scope.chart.setData($scope.data);
                });

            }
        };
        return util;
    }]);

imarServices.factory('ActuatorValues', ['$resource',
    function($resource){
        return $resource('http://imar.local/api/v1.0/actuator/:id/values', {});
    }]);
