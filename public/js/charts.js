var utilizationData = cpu_util.data;
var temperatureData = cpu_temp.data;
var timestampss = timestamps;

function limitToLast20Samples(dataArray) {
    if (dataArray.length > 20) {
        return dataArray.slice(dataArray.length - 20); 
    }
    return dataArray; 
}

utilizationData = limitToLast20Samples(utilizationData);
temperatureData = limitToLast20Samples(temperatureData);
timestampss = limitToLast20Samples(timestampss);

var options = {
    series: [
        {
            name: "Utilization",
            data: utilizationData,
        },
        {
            name: "Temperature",
            data: temperatureData,
        },
    ],
    chart: {
        fontFamily: "inherit",
        type: "area",
        height: 350,
        toolbar: {
            show: false,
        },
    },
    plotOptions: {},

    dataLabels: {
        enabled: false,
    },
    fill: {
        type: "gradient",
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0,
            stops: [0, 80, 100],
        },
    },
    stroke: {
        curve: "smooth",
        show: true,
        width: 3,
    },
    xaxis: {
        type: "datetime",
        categories: timestampss, // Use dynamic timestamps here
        labels: {
            formatter: function (value) {
                return new Date(value).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                }); // Format the time to HH:MM AM/PM
            },
        },
        tooltip: {
            x: {
                format: "dd/MM/yy HH:mm",
            },
        },
    },
    yaxis: {
        tickAmount: 8,
        labels: {
            style: {
                colors: "#9fa7bc",
                fontSize: "12px",
            },
            formatter: function (e) {
                return e;
            },
        },
    },
    axisBorder: {
        show: false,
    },
    axisTicks: {
        show: false,
    },
    tickAmount: 6,
    labels: {
        rotate: 0,
        rotateAlways: true,
        style: {
            colors: "#f1f3f7",
            fontSize: "12px",
        },
    },
    crosshairs: {
        position: "front",
        stroke: {
            color: "#f1f3f7",
            width: 1,
            dashArray: 3,
        },
    },
    grid: {
        borderColor: "#f1f3f7",
        strokeDashArray: 4,
        yaxis: {
            lines: {
                show: true,
            },
        },
    },
    tooltip: {
        x: {
            format: "dd/MM/yy HH:mm",
        },
    },
};

var chart = new ApexCharts(
    document.querySelector("#cpu_temp_utilGraph"),
    options
);
chart.render();
document.addEventListener('livewire:navigate', () => {
    if (chart) {
        chart.destroy();
    }
});

$(document).ready(function () {
    window.Echo.private("cpu-graph." + currentDeviceId).listen(
        ".cpu.graph.update",
        (e) => {
            const currentTime = new Date();

            const formattedTime = new Intl.DateTimeFormat("en-PH", {
                timeZone: "Asia/Manila",
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: true,
            }).format(currentTime);

            const isoTime = new Date(formattedTime).toISOString();

            utilizationData.push(e.util);
            temperatureData.push(e.temp);
            timestampss.push(isoTime);

            chart.updateSeries([
                {
                    name: "Utilization",
                    data: utilizationData,
                },
                {
                    name: "Temperature",
                    data: temperatureData,
                },
            ]);

            const maxDataPoints = 20;
            if (utilizationData.length > maxDataPoints) {
                utilizationData.shift();
                temperatureData.shift();
                timestampss.shift();
            }
        }
    );
});