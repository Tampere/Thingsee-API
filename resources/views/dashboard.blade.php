<!DOCTYPE html>
<html>
    <head>
        <title>ThingSee data API Dashboard</title>
        <script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['gauge', 'corechart', 'line']}]}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <style type="text/css">
            .container
            {
                width: 960px;
                margin: 20px auto;
            }

            .container p, h1
            {
                font-family: lato; 
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>ThingSee data API Dashboard</h1>
            
            <div id="chart_div_1" style="float:left"></div><div id="chart_div_2" style="float: left"></div><div id="chart_div_3" style="float: left"></div><div id="chart_div_4" style="float: left"></div>
            <div id="chart_div_5" style="clear: both;"></div>
        </div>

        <script type="text/javascript">
        var valuesTemperature = [];
        var valuesLuminance = [];
        var currentTemp, currentHumidity, currentPressure, currentLuminance;
        var currentTs;
        var chart_div_1 = document.getElementById('chart_div_1');
        var chart_div_2 = document.getElementById('chart_div_2');
        var chart_div_3 = document.getElementById('chart_div_3');
        var chart_div_4 = document.getElementById('chart_div_4');
        var chart_div_5 = document.getElementById('chart_div_5');
        var chartTemp = new google.visualization.Gauge(chart_div_1);
        var chartHumi = new google.visualization.Gauge(chart_div_2);
        var chartPress = new google.visualization.Gauge(chart_div_3);
        var chartLumi = new google.visualization.Gauge(chart_div_4);
        var formatter = new google.visualization.DateFormat({pattern: "dd'/'MM' 'HH':'mm"});

        (function(){
            $.getJSON('http://www.avoindata.net/thingsee/v1/envs/2/data?sensor=0x00060100', function(json) {
//            $.getJSON('v1/envs/2/data?sensor=0x00060100', function(json) {
                valuesTemperature = json['data'];
                currentTemp = parseFloat(json['data'][0]['sensor']['value']);
                currentTs = new Date(parseInt(json['data'][0]['timestamp']));

                var data = google.visualization.arrayToDataTable([
                  ['Label', 'Value'],
                  ['Temp', currentTemp]
                ]);

                $('.container').append('<p>Last update was: ' + currentTs + '</p>');
                chartTemp.draw(data, optionsTemp);
                drawLineChart();
            });

            $.getJSON('http://www.avoindata.net/thingsee/v1/envs/2/data?sensor=0x00060200&limit=1', function(json) {
//            $.getJSON('v1/envs/2/data?sensor=0x00060200', function(json) {
                currentHumidity = parseFloat(json['data'][0]['sensor']['value']);

                var data = google.visualization.arrayToDataTable([
                  ['Label', 'Value'],
                  ['Humidity', currentHumidity]
                ]);

                chartHumi.draw(data, optionsHumidity);

            });

            $.getJSON('http://www.avoindata.net/thingsee/v1/envs/2/data?sensor=0x00060300', function(json) {
//            $.getJSON('v1/envs/2/data?sensor=0x00060300', function(json) {
                valuesLuminance = json['data'];
                currentPressure = parseFloat(json['data'][0]['sensor']['value']);

                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Pressure', currentPressure]
                ]);

                chartPress.draw(data, optionsPressure);

            });

            $.getJSON('http://www.avoindata.net/thingsee/v1/envs/2/data?sensor=0x00060400&limit=1', function(json) {
//            $.getJSON('v1/envs/2/data?sensor=0x00060400&limit=1', function(json) {
                currentLuminance = parseFloat(json['data'][0]['sensor']['value']);

                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Luminance', currentLuminance]
                ]);

                chartLumi.draw(data, optionsLuminosity);

            });

            var optionsTemp = {
              width: 300, height: 210,
              redFrom: 35, redTo: 50,
              yellowFrom:-50, yellowTo: -35,
                yellowColor: "#4679BD",
              minorTicks: 5,
                min: -50,
                max: 50
            };

            var optionsHumidity = {
              width: 300, height: 210,
              redFrom: 90, redTo: 100,
              yellowFrom: 80, yellowTo: 90,
              yellowColor: "#4679BD",
              minorTicks: 5,
              min: 0,
              max: 100
            };

            var optionsPressure = {
                width: 300, height: 210,
                minorTicks: 5,
                min: 0,
                max: 1000
            };

            var optionsLuminosity = {
                width: 300, height: 210,
                minorTicks: 5,
                min: 0,
                max: 5000
            };
        })();

        function drawLineChart()
        {
            var values = [];
            for(var i = 0, len = valuesTemperature.length; i < len; i++)
            {
                values.push([
                        valuesTemperature[i]['timestamp'],
                        valuesTemperature[i]['sensor']['value'],
                        valuesLuminance[i]['sensor']['value'] / 100
                ]);
            }
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'X');
            data.addColumn('number', 'Temperature');
            data.addColumn('number', 'Luminance (x100)');
            $.each(values, function(key, val) {
                var tmpDate = new Date(parseInt(val[0]));
                data.addRow([tmpDate, parseFloat(val[1]), parseFloat(val[2])]);
            });

            formatter.format(data, 0);

            var options = {
                chart: {
                   title: 'Values charted'
                },
                height: 500,
                hAxis: {
                  title: 'Timestamp',
                  gridlines: {
                    count: 10
                  }
                },
                vAxis: {
                  title: 'Values',
                }
            };

            var chart = new google.visualization.LineChart(chart_div_5);

            chart.draw(data, options);
        }
        </script>
    </body>
</html>
