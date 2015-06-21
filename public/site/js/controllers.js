var imarControllers = angular.module('imarControllers', []);

imarControllers.controller('sensorListCtrl', ['$scope', '$http', 'Sensor', 'Util',
    function ($scope, $http, Sensor, Util) {
        console.log('sensor controller called');
        $scope.redirect = Util.redirect;
        Sensor.get({}, function (response) {
            $scope.sensors = response.sensors;
            console.log(response.sensors);
        });
    }]);

imarControllers.controller('actuatorListCtrl', ['$scope', '$http', 'Actuator', 'Util',
    function ($scope, $http, Actuator, Util) {
        Actuator.get({}, function (response) {
            $scope.redirect = Util.redirect;
            $scope.actuators = response.actuators;
            console.log(response);
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
            console.log(response);
            $scope.sensor = response.sensor;
        });
    }]);

imarControllers.controller('sensorSingleValuesCtrl', ['$scope', '$routeParams','Sensor', 'SensorValues',
    function ($scope, $routeParams,Sensor, SensorValues) {
        console.log('sensor single values controller called');
        //$scope.redirect = Util.redirect;
        SensorValues.get({id: $routeParams.id}, function (response) {
            console.log(response);
            $scope.sensorValues = response.sensorValues;
            $scope.sensor = response.sensor;
            $scope.sensor.latest_value = response.latest_value;
            $scope.dates = [];
            $scope.data = [];
            $scope.chart;

            for(var i = 0; i < $scope.sensorValues.length; i++) {
                // Split timestamp into [ Y, M, D, h, m, s ]
                var t = $scope.sensorValues[i].created_at.split(/[- :]/);

                // Apply each element to the Date function
                var date = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                //console.log(date);
                //var month = date.toLocaleString("en-US", { month: "long" });
                //var day = date.getDay();
                $scope.dates.push([date.getTime(), parseInt($scope.sensorValues[i].value)]);

                $scope.data.push({
                    period: date.getTime(),
                    value: parseInt($scope.sensorValues[i].value)
                })
            }

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
                console.log(new Date());
                $('#end-time').data("DateTimePicker").minDate(e.date);

                var fromDateTime = $(this).find('input').val();
                var fromDateTimeUrlEncoded = encodeURIComponent(fromDateTime);
                SensorValues.get({id: $routeParams.id, from: fromDateTimeUrlEncoded}, function (response) {
                    $scope.sensorValues = response.sensorValues;
                    $scope.sensor = response.sensor;
                    $scope.sensor.latest_value = response.latest_value;
                    $scope.dates = [];
                    $scope.data = [];

                    for(var i = 0; i < $scope.sensorValues.length; i++) {
                        // Split timestamp into [ Y, M, D, h, m, s ]
                        var t = $scope.sensorValues[i].created_at.split(/[- :]/);

                        // Apply each element to the Date function
                        var date = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                        //console.log(date);
                        //var month = date.toLocaleString("en-US", { month: "long" });
                        //var day = date.getDay();
                        $scope.dates.push([date.getTime(), parseInt($scope.sensorValues[i].value)]);

                        $scope.data.push({
                            period: date.getTime(),
                            value: parseInt($scope.sensorValues[i].value)
                        })
                    }

                    $scope.chart.setData($scope.data);
                });
            });

            $('#end-time').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $("#end-time").on("dp.change", function (e) {
                $('#start-time').data("DateTimePicker").maxDate(e.date);

                var fromDateTime = $(this).find('input').val();
                var fromDateTimeUrlEncoded = encodeURIComponent(fromDateTime);

                var toDateTime = $(this).find('input').val();
                var toDateTimeUrlEncoded = encodeURIComponent(toDateTime);
                SensorValues.get({id: $routeParams.id, to: toDateTimeUrlEncoded}, function (response) {
                    $scope.sensorValues = response.sensorValues;
                    $scope.sensor = response.sensor;
                    $scope.sensor.latest_value = response.latest_value;
                });
            });

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


imarControllers.controller('actuatorSingleValuesCtrl', ['$scope', '$routeParams', 'Actuator', 'ActuatorValues',
    function ($scope, $routeParams, actuator, ActuatorValues) {
        console.log('actuator single values controller called');
        //$scope.redirect = Util.redirect;
        ActuatorValues.get({id: $routeParams.id}, function (response) {
            console.log(response);
            $scope.actuatorValues = response.actuatorValues;
            $scope.actuator = response.actuator;
            $scope.actuator.latest_value = response.latest_value;
            $scope.dates = [];
            $scope.data = [];

            for(var i = 0; i < $scope.actuatorValues.length; i++) {
                // Split timestamp into [ Y, M, D, h, m, s ]
                var t = $scope.actuatorValues[i].created_at.split(/[- :]/);

                // Apply each element to the Date function
                var date = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
                //console.log(date);
                //var month = date.toLocaleString("en-US", { month: "long" });
                //var day = date.getDay();
                $scope.dates.push([date.getTime(), parseInt($scope.actuatorValues[i].value)]);

                $scope.data.push({
                    period: date.getTime(),
                    value: parseInt($scope.actuatorValues[i].value)
                })
            }

            console.log($scope.data);
            Morris.Line({
                element: 'morris-area-chart',
                data: $scope.data,
                xkey: 'period',
                ykeys: ['value'],
                //xkey: $scope.data[0].period - 2000,
                labels: [$scope.actuator.value_type],
                pointSize: 4,
                hideHover: 'auto',
                resize: true
            });
        });


    }]);