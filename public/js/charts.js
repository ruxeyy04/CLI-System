if (currentDeviceId) {
    // Data Arrays
    var dataArrays = {};
    dataArrays = {
        cpu: {
            utilization: cpu_util.data,
            temperature: cpu_temp.data,
            timestamps: timestamps,
        },
        ram: { usage: ram_usage.data, timestamps: ram_usage.timestamps },
        gpu: {
            usage: gpu_usage.data,
            temperature: gpu_temp.data,
            timestamps: gpu_timestamps,
        },
    };

    function limitToLast20Samples(dataArray) {
        return dataArray.length > 20 ? dataArray.slice(-20) : dataArray;
    }

    function applyLimit() {
        Object.keys(dataArrays).forEach((key) => {
            Object.keys(dataArrays[key]).forEach((subKey) => {
                dataArrays[key][subKey] = limitToLast20Samples(
                    dataArrays[key][subKey]
                );
            });
        });
    }
    applyLimit();

    var borderColor = KTThemeMode.getMode() === "light" ? "#f1f3f7" : "#25282f";
    KTThemeMode.on("kt.thememode.change", () => {
        borderColor = KTThemeMode.getMode() === "light" ? "#f1f3f7" : "#25282f";
        [chart, chart1, chart2].forEach((ch) =>
            ch.updateOptions({ grid: { borderColor } })
        );
    });

    function createChartOptions(series, xCategories) {
        return {
            series,
            chart: {
                fontFamily: "inherit",
                type: "area",
                height: 350,
                toolbar: { show: true },
            },
            dataLabels: { enabled: false },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0,
                    stops: [0, 80, 100],
                },
            },
            stroke: { curve: "smooth", width: 3 },
            xaxis: {
                type: "datetime",
                categories: xCategories,
                labels: {
                    formatter: (value) =>
                        new Date(value).toLocaleTimeString([], {
                            hour: "2-digit",
                            minute: "2-digit",
                        }),
                },
                tooltip: { x: { format: "dd/MM/yy HH:mm" } },
            },
            yaxis:
                series.length === 2
                    ? [
                          {
                              tickAmount: 8,
                              labels: {
                                  style: {
                                      colors: "#9fa7bc",
                                      fontSize: "12px",
                                  },
                                  formatter: (v) => v + " %",
                              },
                          },
                          {
                              opposite: true,
                              tickAmount: 8,
                              labels: {
                                  style: {
                                      colors: "#9fa7bc",
                                      fontSize: "12px",
                                  },
                                  formatter: (v) => v + " °C",
                              },
                          },
                      ]
                    : {
                          tickAmount: 8,
                          labels: {
                              style: { colors: "#9fa7bc", fontSize: "12px" },
                              formatter: (v) => v + " %",
                          },
                      },
            grid: {
                borderColor,
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
            },
            tooltip: {
                y: {
                    formatter: (value, { seriesIndex }) =>
                        seriesIndex === 0 ? value + "%" : value + "°C",
                },
                x: { format: "dd/MM/yy HH:mm" },
            },
        };
    }

    // Create Charts
    var chart;
    var chart1;
    var chart2;
    chart = new ApexCharts(
        document.querySelector("#cpu_temp_utilGraph"),
        createChartOptions(
            [
                { name: "Utilization", data: dataArrays.cpu.utilization },
                { name: "Temperature", data: dataArrays.cpu.temperature },
            ],
            dataArrays.cpu.timestamps
        )
    );

    chart1 = new ApexCharts(
        document.querySelector("#ram_usage_graph"),
        createChartOptions(
            [{ name: "RAM Usage", data: dataArrays.ram.usage }],
            dataArrays.ram.timestamps
        )
    );

    chart2 = new ApexCharts(
        document.querySelector("#gpu_usage_graph"),
        createChartOptions(
            [
                { name: "Usage", data: dataArrays.gpu.usage },
                { name: "Temperature", data: dataArrays.gpu.temperature },
            ],
            dataArrays.gpu.timestamps
        )
    );

    chart.render();
    chart1.render();
    chart2.render();

    document.addEventListener("livewire:navigate", () =>
        [chart, chart1, chart2].forEach((ch) => ch?.destroy())
    );

    function handleRealtimeUpdates(
        channelName,
        dataType,
        chartInstance,
        seriesData,
        maxPoints = 20
    ) {
        window.Echo.private(`${channelName}.${currentDeviceId}`).listen(
            `.${dataType}.graph.update`,
            (e) => {
                const currentTime = new Date().toISOString();
                seriesData.forEach(({ dataArray, value }) =>
                    dataArrays[dataType][dataArray].push(e[value])
                );

                chartInstance.updateSeries(
                    seriesData.map(({ dataArray, name }) => ({
                        name,
                        data: dataArrays[dataType][dataArray],
                    }))
                );

                Object.keys(dataArrays[dataType]).forEach((key) => {
                    if (dataArrays[dataType][key].length > maxPoints)
                        dataArrays[dataType][key].shift();
                });
            }
        );
    }

    $(document).ready(function () {
        if (currentDeviceId) {
            handleRealtimeUpdates("cpu-graph", "cpu", chart, [
                {
                    dataArray: "utilization",
                    value: "util",
                    name: "Utilization",
                },
                {
                    dataArray: "temperature",
                    value: "temp",
                    name: "Temperature",
                },
            ]);
            handleRealtimeUpdates("gpu-graph", "gpu", chart2, [
                { dataArray: "usage", value: "usage", name: "Usage" },
                {
                    dataArray: "temperature",
                    value: "temp",
                    name: "Temperature",
                },
            ]);
            handleRealtimeUpdates("ram-graph", "ram", chart1, [
                { dataArray: "usage", value: "usage", name: "RAM Usage" },
            ]);
        }
    });
}
