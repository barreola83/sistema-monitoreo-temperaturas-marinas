var graphOptions =
{
    series:
    {
        lines:
        {
            show: true
        },
        points:
        {
            show: true
        }
    },
    grid:
    {
        hoverable: true
    },
    xaxis:
    {
        min: 0,
        max: 12
    },
    yaxis:
    {
        min: 0,
        max: 50
    },
    tooltip: true,
    tooltipOpts:
    {
        content: "%s es %y.2",
        shifts:
        {
            x: -60,
            y: 25
        }
    }
};

var graphData =
[
    {
        data: [],
        label: "Temperatura (°C)"
    }
];

var plot = $.plot($("#flot-line-chart"), graphData, graphOptions);

$(function()
{
    /*
    var time = getRefreshTime(1);
    window.setInterval(function()
    {
        var xml = getLastestData(1);
    }, time);

    document.getElementById("sensor-1").className = "panel panel-red";
    $("#sensor-state-1").html("Desconectado");
    */
});

selectSensor.counter = 0;
selectSensor.sensorID = null;
selectSensor.intervalID = null;

function selectSensor(sensorID)
{
    if (selectSensor.sensorID == null)
    {
        var time = getRefreshTime(sensorID);

        eraseTableData();
        $("#selectButton").html("Sensor " + sensorID + " <span class=\"caret\"></span>");

        selectSensor.sensorID = sensorID;
        selectSensor.intervalID = window.setInterval(function()
        {
            var xml = getLastestData(sensorID);

            if (xml != null)
            {
                updateTable(xml);
                updateGraph(xml);
            }
        }, time);
    }
    else if (selectSensor.sensorID != sensorID)
    {
        var time = getRefreshTime(sensorID);

        window.clearInterval(selectSensor.intervalID);
        eraseTableData();
        $("#selectButton").html("Sensor " + sensorID + " <span class=\"caret\"></span>");

        selectSensor.sensorID = sensorID;
        selectSensor.intervalID = window.setInterval(function()
        {
            var xml = getLastestData(sensorID);

            if (xml != null)
            {
                updateTable(xml);
                updateGraph(xml);
            }
        }, time);
    }
}

function getRefreshTime(sensorID)
{
    var time = 0;
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            time = parseInt(this.responseText) * 1000;
        }
    };

    xmlhttp.open("GET", "../php/get-sensors-info/get-sensor-refresh-time.php?id=" + sensorID, false);
    xmlhttp.send();

    return time;
}

function getLastestData(sensorID)
{
    var data;
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            data = this.responseText;
        }
    };

    xmlhttp.open("GET", "../php/get-sensors-info/get-last-sensor-data-received.php?id=" + sensorID, false);
    xmlhttp.send();

    if (data === "noresults")
        return null;

    return new DOMParser().parseFromString(data, "text/xml");
}

updateTable.lastXML = null;

function updateTable(xml)
{
    if (updateTable.lastXML == null)
    {
        addRow(xml);
        updateTable.lastXML = xml;
    }
    else if (!compareToLastXML(updateTable.lastXML, xml))
    {
        addRow(xml);
        updateTable.lastXML = xml;
    }
}

function addRow(xml)
{
    var table = document.getElementById("dataTable").getElementsByTagName("tbody")[0];
    var row = table.insertRow(table.rows.length);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);
    var cell6 = row.insertCell(5);
    var text;

    text = document.createTextNode(getNodeValue(xml, "idsensor"));
    cell1.appendChild(text);
    text = document.createTextNode("Sensor " + getNodeValue(xml, "idsensor"));
    cell2.appendChild(text);
    text = document.createTextNode(getNodeValue(xml, "temperature"));
    cell3.appendChild(text);
    text = document.createTextNode(getNodeValue(xml, "lightlevel"));
    cell4.appendChild(text);
    text = document.createTextNode(getNodeValue(xml, "time"));
    cell5.appendChild(text);
    text = document.createTextNode(getNodeValue(xml, "date"));
    cell6.appendChild(text);
}

function eraseTableData()
{
    var tableHeaderRowCount = 1;
    var table = document.getElementById('dataTable');
    var rowCount = table.rows.length;

    for (var i = tableHeaderRowCount; i < rowCount; i++)
    {
        table.deleteRow(tableHeaderRowCount);
    }
}

updateGraph.lastXML = null;

function updateGraph(xml)
{
    if (updateGraph.lastXML == null)
    {
        addData(xml);
        updateGraph.lastXML = xml;
    }
    else if (!compareToLastXML(updateGraph.lastXML, xml))
    {
        addData(xml);
        updateGraph.lastXML = xml;
    }
}

function addData(xml)
{
    if (graphData[0].data.length === graphOptions.xaxis.max)
    {
        var data = [];

        for (var i = 1; i < graphOptions.xaxis.max; i++)
        {
            data.push([i - 1, graphData[0].data[i][1]]);
        }

        graphData[0].data = data;
        graphData[0].data.push([graphData[0].data.length, getNodeValue(xml, "temperature")]);
        plot.setData(graphData);
        plot.draw();
    }
    else if (graphData[0].data.length > 0)
    {
        graphData[0].data.push([graphData[0].data.length, getNodeValue(xml, "temperature")]);
        plot.setData(graphData);
        plot.draw();
    }
    else
    {
        graphData[0].data.push([0, getNodeValue(xml, "temperature")]);
        plot.setData(graphData);
        plot.draw();
    }
}

function compareToLastXML(lastXML, newXML)
{
    var tagNames = ["date", "time"];
    var flag = 0;

    for (var i = 0; i < tagNames.length; i++)
    {
        if (getNodeValue(lastXML, tagNames[i]) === getNodeValue(newXML, tagNames[i]))
        {
            flag++;
        }
    }

    if (flag === 2)
    {
        return true;
    }

    return false;
}

function getNodeValue(xml, tagName)
{
    return xml.getElementsByTagName(tagName)[0].childNodes[0].nodeValue;
}
