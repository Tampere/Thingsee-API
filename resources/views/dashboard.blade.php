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
            
            <div id="chart_div_1" style="float:left"></div><div id="chart_div_2" style="float: left"></div>
            <div id="chart_div_3" style="clear: both;"></div>
        </div>

        <script type="text/javascript">
        var valuesTemperature = [];
        var valuesHumidity = [];
        var currentTemp, currentHumidity;
        var currentTs;
        var chart_div_1 = document.getElementById('chart_div_1');
        var chart_div_2 = document.getElementById('chart_div_2');
        var chart_div_3 = document.getElementById('chart_div_3');
        var chartTemp = new google.visualization.Gauge(chart_div_1);
        var chartHumi = new google.visualization.Gauge(chart_div_2);
        var formatter = new google.visualization.DateFormat({pattern: "dd'/'MM' 'HH':'mm"});

        (function(){
            $.getJSON('http://www.avoindata.net/thingsee/v1/events?sensor=0x00060100', function(json) {
                valuesTemperature = json;
                currentTemp = parseFloat(json[0]['val']);
                currentTs = new Date(parseInt(json[0]['ts']));

                var data = google.visualization.arrayToDataTable([
                  ['Label', 'Value'],
                  ['Temp', currentTemp]
                ]);

                $('.container').append('<p>Last update was: ' + currentTs + '</p>');
                chartTemp.draw(data, optionsTemp);
                drawLineChart();
            });

            $.getJSON('http://www.avoindata.net/thingsee/v1/events?sensor=0x00060200', function(json) {
                valuesHumidity = json;
                currentHumidity = parseFloat(json[0]['val']);

                var data = google.visualization.arrayToDataTable([
                  ['Label', 'Value'],
                  ['Humidity', currentHumidity]
                ]);

                chartHumi.draw(data, optionsHumidity);

            });            

            var optionsTemp = {
              width: 600, height: 420,
              redFrom: 35, redTo: 50,
              yellowFrom:-50, yellowTo: -35,
                yellowColor: "#4679BD",
              minorTicks: 5,
                min: -50,
                max: 50
            };

            var optionsHumidity = {
              width: 600, height: 420,
              redFrom: 90, redTo: 100,
              yellowFrom: 80, yellowTo: 90,
              yellowColor: "#4679BD",
              minorTicks: 5,
              min: 0,
              max: 100
            };
        })();

        function drawLineChart()
        {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'X');
            data.addColumn('number', 'Temperature');
            $.each(valuesTemperature, function(key, val) {
                var tmpDate = new Date(parseInt(val['ts']));
                data.addRow([tmpDate, parseFloat(val['val'])]);
            });

            formatter.format(data, 0);

            var options = {
                hAxis: {
                  title: 'Timestamp'
                },
                vAxis: {
                  title: 'Temperature'
                }
            };

            var chart = new google.visualization.LineChart(chart_div_3);

            chart.draw(data, options);
        }
        </script>
    </body>
</html>
