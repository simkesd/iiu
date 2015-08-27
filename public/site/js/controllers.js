var imarControllers = angular.module('imarControllers', []);

imarControllers.controller('welcomeCtrl', ['$scope',
    function ($scope) {
        console.log('welcome controller called');
    }]);

/**********************************
 **********************************
 *          SENSORS
 **********************************
 **********************************/

imarControllers.controller('sensorListCtrl', ['$scope', '$http', 'Sensor', 'Util',
    function ($scope, $http, Sensor, Util) {
        console.log('sensor controller called');
        $scope.redirect = Util.redirect;
        Sensor.get({}, function (response) {
            $scope.sensors = response.sensors;
            console.log(response.sensors);
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

imarControllers.controller('sensorValueAddCtrl', ['$scope', '$routeParams', 'Sensor', 'SensorValues',
    function ($scope, $routeParams, Sensor, SensorValues) {
        console.log('sensor value add controller called');
        $scope.sensorValue = {};
        $scope.master = {};

        $scope.update = function (sensorValue) {
            $scope.master = angular.copy(sensorValue);
            $scope.master.sensor_id = $routeParams.id;
            SensorValues.save({id: $routeParams.id, value: sensorValue.value}, sensorValue);
        };
    }]);

imarControllers.controller('sensorSingleCtrl', ['$scope', '$routeParams', 'Sensor',
    function ($scope, $routeParams, Sensor) {
        console.log('sensor add controller called');

        Sensor.get({id: $routeParams.id}, function (response) {
            console.log(response);
            $scope.sensor = response.sensor;
        });
    }]);

imarControllers.controller('sensorSingleValuesCtrl', ['$scope', '$routeParams', 'Sensor', 'SensorValues', 'Util',
    function ($scope, $routeParams, Sensor, SensorValues, Util) {
        console.log('sensor single values controller called');
        //$scope.redirect = Util.redirect;
        SensorValues.get({id: $routeParams.id}, function (response) {
            console.log(response);
            $scope.sensorValues = response.sensorValues;
            $scope.sensor = response.sensor;
            $scope.sensor.latest_value = response.latest_value;
            $scope.data = Util.datesFromSensorValues($scope.sensorValues);
            $scope.chart;

            console.log($scope.data);

            $scope.chart = Morris.Line({
                element: 'morris-area-chart',
                data: $scope.data,
                xkey: 'period',
                ykeys: ['value'],
                //xkey: $scope.data[0].period - 2000,
                labels: [$scope.sensor.value_type],
                pointSize: 4,
                hideHover: 'auto',
                resize: true
            });

            $('#start-time').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $("#start-time").on("dp.change", function (e) {
                $('#end-time').data("DateTimePicker").minDate(e.date);

                var fromDateTime = $(this).find('input').val();
                var toDateTime = $('#end-time').find('input').val();

                Util.handleDatePickerChangeEvent($scope, 'sensor', fromDateTime, toDateTime);
            });

            $('#end-time').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $("#end-time").on("dp.change", function (e) {
                $('#start-time').data("DateTimePicker").maxDate(e.date);

                var fromDateTime = $('#start-time').find('input').val();
                var toDateTime = $(this).find('input').val();

                Util.handleDatePickerChangeEvent($scope, 'sensor', fromDateTime, toDateTime);
            });

        });


    }]);

/**********************************
 **********************************
 *          ACTUATORS
 **********************************
 **********************************/

imarControllers.controller('actuatorListCtrl', ['$scope', '$http', 'Actuator', 'Util',
    function ($scope, $http, Actuator, Util) {
        Actuator.get({}, function (response) {
            $scope.redirect = Util.redirect;
            $scope.actuators = response.actuators;
            console.log(response);
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
            $scope.actuator = response.actuator;
        });
    }]);

imarControllers.controller('actuatorSingleValuesCtrl', ['$scope', '$routeParams', 'Actuator', 'ActuatorValues', 'ActuatorValuesDaily', '$route', 'Util', 'ElectricityPrice',
    function ($scope, $routeParams, Actuator, ActuatorValues, ActuatorValuesDaily, $route, Util, ElectricityPrice) {
        console.log('actuator single values controller called');
        $scope.chart;

        ActuatorValuesDaily.get({id: $routeParams.id}, function(response) {
            $scope.details = response.data;

            $scope.cumulative = {};
            $scope.cumulative.cost = 0;
            $scope.cumulative.kwSpent = 0;
            $scope.cumulative.periodOn = 0;
            $scope.cumulative.periodOff = 0;

            var currentDay = $scope.details[0];
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

        ActuatorValues.get({id: $routeParams.id}, function (response) {
            $scope.actuatorValues = response.actuatorValues;
            $scope.actuator = response.actuator;
            $scope.actuator.latest_value = response.latest_value;
            $scope.periodOn = response.periodOn;
            $scope.periodOff = response.periodOff;
            $scope.data = Util.datesFromSensorValues($scope.actuatorValues);

            $('#start-time').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $("#start-time").on("dp.change", function (e) {
                $('#end-time').data("DateTimePicker").minDate(e.date);

                var fromDateTime = $(this).find('input').val();
                var toDateTime = $('#end-time').find('input').val();

                Util.handleDatePickerChangeEvent($scope, 'actuator', fromDateTime, toDateTime);

            });

            $('#end-time').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $("#end-time").on("dp.change", function (e) {
                $('#start-time').data("DateTimePicker").maxDate(e.date);

                var fromDateTime = $('#start-time').find('input').val();
                var toDateTime = $(this).find('input').val();

                Util.handleDatePickerChangeEvent($scope, 'actuator', fromDateTime, toDateTime);

            });

            //$scope.chart = Morris.Line({
            //    element: 'morris-area-chart',
            //    data: $scope.data,
            //    xkey: 'period',
            //    ykeys: ['value'],
            //    //xkey: $scope.data[0].period - 2000,
            //    labels: ['State'],
            //    pointSize: 4,
            //    hideHover: 'auto',
            //    resize: true
            //});

            //$scope.donutChart = Morris.Donut({
            //    element: 'morris-donut-chart',
            //    data: [
            //        {label: "Minutes On", value: response.periodOn},
            //        {label: "Minutes off", value: response.periodOff}
            //    ]
            //});

            $scope.changeActuatorState = function () {
                $scope.actuator.latest_value.value = ($scope.actuator.latest_value.value == 0) ? 1 : 0;
                ActuatorValues.save({
                        id: $routeParams.id,
                        value: $scope.actuator.latest_value.value
                    },
                    $scope.sensorValues).$promise.then(function () {
                        $route.reload();
                    });
            };

            ElectricityPrice.get({id: $routeParams.id}, function (response) {
                //console.log('price', response);
            });
        });
    }]);