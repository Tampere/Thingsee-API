#ThingSee One data API

Provides an API for querying data from [ThingSee One](http://www.thingsee.com/) IOT devices that will be placed around the city of Tampere.

##Overview

The devices push data to our server every 10 minutes via GPRS connection. Currently the data includes temperature (C), humidity (% rH), pressure (hPa) and luminance (lux).

##API Usage

Prefix all requests with API version, currently /v1

###Persisting data
The API responds to HTTP POST at /events. The payload is assumed to be exactly what the ThingSee Cloud pushes.

###Reading data
The  API responds to HTTP GET at /envs, which will return a list of devices.
Each device has links array pointing to self (envs/{:id}) and data (envs/{:id}/data).

Each data block has:

- device array with id and link to self
- sensor array with human readable sensor type, sensor id and value
- timestamp with high precision
- human readable time

After data blocks:

- Meta-data with number of records and limit
- Links array to prev and next result set if present, null otherwise

Possible parameters are:
/data{sensor?}{limit?}

- Sensor - a sensor or a list of sensors (0x00060400 or 0x00060100,0x00060200,...)
- Limit - How many records to retrieve. If not present, the maxium is set to 50.

####Sensor mapping
- 0x00060100 - Temperature
- 0x00060200 - Humidity
- 0x00060300 - Pressure
- 0x00060400 - Luminance

## Example request

v1/envs/2/data?sensor=0x00060200&limit=10