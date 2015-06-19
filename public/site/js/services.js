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

imarServices.factory('Util', ['$location',
    function($location){
        return {
            redirect: function(path){
                $location.path(path);
            }
        };
    }]);