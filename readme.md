## Internet of intelligent devices - final project

Final project

## Instructions

MySQL workbench and PDF model can be found in /db folder. 

## API calls

API prefix: api/1.0/

    /sensors


    /actuators
    
    
    /collections - shows the group of sensors & actuators that are used in specific room
    
        /sensors - shows the latest measurements for sensors in given collections
            /{id} - latest sensor measurements for specific sensor
                /{id}?from=time - from given time to current time (it can be combined with to)
                /{id}?to=time - from beginning of measurements until present date (it can be combined with from)
                /{id}/predict - calculates the future values of current sensor based on values from earlier measurements
        
        /actuators - latest states of actuators in specific room
            /{id} - latest actuator state
                /{id}?at=time - state of actuator at given time
                /{id}/predict - calculates the future values of current sensor based on values from earlier measurements