#ThingSee One data API

Provides an API for querying data from [ThingSee One](http://www.thingsee.com/) IOT devices that will be placed around the city of Tampere.

##Overview

The devices push data to our server every 10 minutes via GPRS connection. Currently the data includes temperature (C), humidity (% rH), pressure (hPa) and luminance (lux).

##API Usage

Prefix all requests with API version, currently /v1

###Persisting data
The API responds to HTTP POST at /events. The payload is assumed to be exactly what the ThingSee Cloud pushes.

###Reading data
The  API responds to HTTP GET at /events and at /devices.

Hitting /events without URI parameters, a JSON containing the last 50 events is returned.

Possible parameters are:
/events{device?}{sensor?}{limit?}

- Device - the friendly name of a device
- Sensor - a sensor or a list of sensors (0x00060400 or 0x00060100,0x00060200,...)
- Limit - How many records to retrieve

####Sensor mapping
- 0x00060100 - Temperature
- 0x00060200 - Humidity
- 0x00060300 - Pressure
- 0x00060400 - Luminance