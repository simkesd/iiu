var imarServices = angular.module('imarServices', ['ngResource']);

imarServices.factory('Sensor', ['$resource',
    function($resource){
        return $resource('http://imar.local/api/v1.0/sensor/:id', {});
    }]);

imarServices.factory('Actuator', ['$resource',
    function($resource){
        return $resource('http://imar.local/api/v1.0/actuator/:id', {});
    }]);