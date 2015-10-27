<!DOCTYPE html>
<html>
    <head>
        <title>ThingSee data API</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                font-weight: 100;
            }

            .container {
                width: 960px;
                margin: 0 auto;
            }

            .content {
                
            }

            .title {
                font-size: 96px;
                font-family: 'Lato';
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">ThingSee data API</div>
<p>Provides an API for querying data from [ThingSee One](http://www.thingsee.com/) IOT devices that will be placed around the city of Tampere.</p>

<h2>Overview</h2>

<p>The devices push data to our server every 10 minutes via GPRS connection. Currently the data includes temperature (C), humidity (% rH), pressure (hPa) and luminance (lux).</p>

<h2>API Usage</h2>

<h3>Persisting data</h3>
<p>The API responds to HTTP POST at /events. The payload is assumed to be exactly what the ThingSee Cloud pushes.</p>

<h3>Reading data</h3>
<p>The  API responds to HTTP GET at /events and at /devices.</p>

<p>Hitting /events without URI parameters, a JSON containing the last 50 events is returned.</p>

<p>Possible parameters are:<br />
/events{device?}{sensor?}{limit?}</p>


<ul>
    <li>Device - the friendly name of a device</li>
    <li>Sensor - a sensor or a list of sensors (0x00060400 or 0x00060100,0x00060200,...)</li>
    <li>Limit - How many records to retrieve</li>
</ul>

<h4>Sensor mapping</h4>

<ul>
    <li>0x00060100 - Temperature</li>
    <li>0x00060200 - Humidity</li>
    <li>0x00060300 - Pressure</li>
    <li>0x00060400 - Luminance</li>
</ul>

            </div>
        </div>
    </body>
</html>
