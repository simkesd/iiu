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

imarServices.factory('Actuator', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/actuator/:id', {});
    }]);

imarServices.factory('ActuatorValues', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/actuator/:id/values', {});
    }]);

imarServices.factory('ActuatorValuesDaily', ['$resource',
    function ($resource) {
        return $resource('/api/v1.0/actuator/:id/timeLapseData', {});
    }]);

imarServices.factory('Util', ['$location', 'SensorValues', 'ActuatorValues', 'ActuatorValuesDaily', '$routeParams',
    function ($location, SensorValues, ActuatorValues, ActuatorValuesDaily, $routeParams) {
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
                        $scope.data = util.datesFromSensorValues($scope.actuatorValues);

                    });

                    ActuatorValuesDaily.get({id: $routeParams.id, from: fromDateTimeUrlEncoded, to: toDateTimeUrlEncoded}, function(response) {
                        $scope.details = response.data;

                        $scope.cumulative = {};
                        $scope.cumulative.cost = 0;
                        $scope.cumulative.kwSpent = 0;
                        $scope.cumulative.periodOn = 0;
                        $scope.cumulative.periodOff = 0;

                        var byDays = {};

                        for(var i = 0; i < $scope.details.length; i++) {
                            var date = new Date($scope.details[i].created_at);

                            if(!byDays[date.getMonth()]) {
                                byDays[date.getMonth()] = {};
                                byDays[date.getMonth()].cost = parseInt($scope.details[i].cost);
                                byDays[date.getMonth()].kwSpent = parseInt($scope.details[i].kw_spent);
                                byDays[date.getMonth()].periodOn = $scope.details[i].period_on;
                                byDays[date.getMonth()].periodOff = $scope.details[i].period_off;
                                byDays[date.getMonth()].zone = $scope.details[i].current_zone;

                            }else {
                                byDays[date.getMonth()].cost += parseInt($scope.details[i].cost);
                                byDays[date.getMonth()].kwSpent += parseInt($scope.details[i].kw_spent);
                                byDays[date.getMonth()].periodOn += $scope.details[i].period_on;
                                byDays[date.getMonth()].periodOff += $scope.details[i].period_off;
                                byDays[date.getMonth()].zone = $scope.details[i].current_zone;
                            }


                            $scope.cumulative.cost += parseInt($scope.details[i].cost);
                            $scope.cumulative.kwSpent += parseInt($scope.details[i].kw_spent);
                            $scope.cumulative.periodOn += parseInt($scope.details[i].period_on);
                            $scope.cumulative.periodOff += parseInt($scope.details[i].period_off);
                        }

                        $scope.byDays = byDays;
                    });

                    return true;
                } else {

                }
            }
        };
        return util;
    }]);

imarServices.factory('ElectricityPrice', ['$resource',
    function ($resource) {
        return $resource(root+'/api/v1.0/actuator/:id/calculatePrice', {});
    }]);