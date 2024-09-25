// CPU
var utilizationData = cpu_util.data;
var temperatureData = cpu_temp.data;
var timestampss = timestamps;
// RAM
var ramUsage = ram_usage.data;
var ramTimestamps = ram_usage.timestamps;

// GPU
var gpuUsage = gpu_usage.data;
var gpuTemp = gpu_temp.data;
var gpuTimestamps = gpu_timestamps;

function limitToLast20Samples(dataArray) {
    if (dataArray.length > 20) {
        return dataArray.slice(dataArray.length - 20);
    }
    return dataArray;
}
// CPU
utilizationData = limitToLast20Samples(utilizationData);
temperatureData = limitToLast20Samples(temperatureData);
timestampss = limitToLast20Samples(timestampss);
// RAM
ramUsage = limitToLast20Samples(ramUsage);
ramTimestamps = limitToLast20Samples(ramTimestamps);
// GPU
gpuUsage = limitToLast20Samples(gpuUsage);
gpuTemp = limitToLast20Samples(gpuTemp);
gpuTimestamps = limitToLast20Samples(gpuTimestamps);
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
            show: true,
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
        categories: timestampss,
        labels: {
            formatter: function (value) {
                return new Date(value).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                });
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
var options1 = {
    series: [
        {
            name: "RAM Usage",
            data: ramUsage,
        },
    ],
    chart: {
        fontFamily: "inherit",
        type: "area",
        height: 350,
        toolbar: {
            show: true,
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
        categories: ramTimestamps,
        labels: {
            formatter: function (value) {
                return new Date(value).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                });
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
            formatter: function (value) {
                return value + " %"; // Add the percentage symbol here
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
var options2 = {
    series: [
        {
            name: "Usage",
            data: gpuUsage,
        },
        {
            name: "Temperature",
            data: gpuTemp,
        },
    ],
    chart: {
        fontFamily: "inherit",
        type: "area",
        height: 350,
        toolbar: {
            show: true,
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
        categories: gpuTimestamps,
        labels: {
            formatter: function (value) {
                return new Date(value).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                });
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
document.addEventListener("livewire:navigate", () => {
    if (chart) {
        chart.destroy();
    }
    if (chart1) {
        chart1.destroy();
    }
    if (chart2) {
        chart2.destroy();
    }
});

if (currentDeviceId) {
    var chart = new ApexCharts(
        document.querySelector("#cpu_temp_utilGraph"),
        options
    );
    chart.render();
    var chart1 = new ApexCharts(
        document.querySelector("#ram_usage_graph"),
        options1
    );
    chart1.render();
    var chart2 = new ApexCharts(
        document.querySelector("#gpu_usage_graph"),
        options2
    );
    chart2.render();
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
        window.Echo.private("gpu-graph." + currentDeviceId).listen(
            ".gpu.graph.update",
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

                gpuUsage.push(e.usage);
                gpuTemp.push(e.temp);
                gpuTimestamps.push(isoTime);

                chart2.updateSeries([
                    {
                        name: "Usage",
                        data: gpuUsage,
                    },
                    {
                        name: "Temperature",
                        data: gpuTemp,
                    },
                ]);

                const maxDataPoints = 20;
                if (gpuUsage.length > maxDataPoints) {
                    gpuUsage.shift();
                    gpuTemp.shift();
                    gpuTimestamps.shift();
                }
            }
        );
    });
    $(document).ready(function () {
        window.Echo.private("ram-graph." + currentDeviceId).listen(
            ".ram.graph.update",
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
                ramUsage.push(e.usage);
                ramTimestamps.push(isoTime);

                chart1.updateSeries([
                    {
                        name: "RAM Usage",
                        data: ramUsage,
                    },
                ]);

                const maxDataPoints = 20;
                if (ramUsage.length > maxDataPoints) {
                    ramUsage.shift();
                    ramTimestamps.shift();
                }
            }
        );
    });
}
