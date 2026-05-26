const LAB_CHART_TIMEZONE = "Asia/Manila";

function formatLabTimestamp(date = new Date()) {
    const parts = new Intl.DateTimeFormat("en-CA", {
        timeZone: LAB_CHART_TIMEZONE,
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false,
    }).formatToParts(date);

    const get = (type) => parts.find((p) => p.type === type)?.value ?? "00";

    return `${get("year")}-${get("month")}-${get("day")} ${get("hour")}:${get("minute")}:${get("second")}`;
}

function parseChartTimestamp(value) {
    if (value == null) {
        return Date.now();
    }

    if (typeof value === "number") {
        return value;
    }

    const raw = String(value).trim();

    if (/^\d+$/.test(raw)) {
        return Number(raw);
    }

    const normalized = raw.includes("T")
        ? raw.replace(/\.\d+Z?$/, "").replace("Z", "").replace("T", " ")
        : raw;

    return new Date(normalized.replace(" ", "T")).getTime();
}

function formatChartAxisTime(value) {
    return formatSchedulerLabel(value);
}

/** Wall-clock label from "Y-m-d H:i:s" — matches time_scheduler slots exactly. */
function formatSchedulerLabel(stamp) {
    const raw = String(stamp ?? "").trim();

    if (!raw) {
        return "";
    }

    const timePart = raw.includes(" ") ? raw.split(" ")[1] : raw;
    const segments = timePart.split(":").map((part) => parseInt(part, 10));

    if (segments.length < 2 || Number.isNaN(segments[0]) || Number.isNaN(segments[1])) {
        return raw;
    }

    const hour24 = segments[0];
    const minute = segments[1];
    const period = hour24 >= 12 ? "PM" : "AM";
    const hour12 = hour24 % 12 || 12;

    return `${String(hour12).padStart(2, "0")}:${String(minute).padStart(2, "0")} ${period}`;
}

/** One x-axis label per reading (matches scheduler slots, not auto-spaced ticks). */
function buildChartCategories(timestamps) {
    return (timestamps || []).map((stamp) => formatSchedulerLabel(stamp));
}

var graphStartDate = "";
var graphEndDate = "";

function isMultiDayGraphRange() {
    return Boolean(
        graphStartDate && graphEndDate && graphStartDate !== graphEndDate
    );
}

function buildGraphCategoryLabels(timestamps) {
    return isMultiDayGraphRange()
        ? buildTrendCategories(timestamps)
        : buildChartCategories(timestamps);
}

function buildGraphCategoryXaxis(timestamps) {
    const categories = buildGraphCategoryLabels(timestamps);

    return {
        type: "category",
        categories,
        tickPlacement: "on",
        labels: {
            rotate: categories.length > 8 ? -45 : -45,
            rotateAlways: false,
            hideOverlappingLabels: false,
            trim: false,
            style: {
                colors: "#9fa7bc",
                fontSize: categories.length > 12 ? "9px" : "11px",
            },
        },
        axisTicks: {
            show: true,
        },
        tooltip: {
            enabled: false,
        },
    };
}

function setGraphDateRange(startDate, endDate) {
    graphStartDate = startDate || "";
    graphEndDate = endDate || startDate || "";
}

function toNumericSeries(values) {
    return (values || []).map((value) => {
        if (value == null || value === "") {
            return null;
        }

        const number = Number(value);

        return Number.isNaN(number) ? null : number;
    });
}

function buildCategoryXaxis(timestamps) {
    return {
        type: "category",
        categories: buildChartCategories(timestamps),
        tickPlacement: "on",
        labels: {
            rotate: -45,
            rotateAlways: false,
            hideOverlappingLabels: false,
            trim: false,
            style: {
                colors: "#9fa7bc",
                fontSize: "11px",
            },
        },
        axisTicks: {
            show: true,
        },
        tooltip: {
            enabled: false,
        },
    };
}

function resolveChartPointIndex(value, opts) {
    if (opts?.dataPointIndex != null && !Number.isNaN(opts.dataPointIndex)) {
        return opts.dataPointIndex;
    }

    if (value == null || value === "") {
        return null;
    }

    const numeric = parseInt(String(value), 10);

    if (Number.isNaN(numeric)) {
        return null;
    }

    // ApexCharts category tooltips often pass a 1-based index (e.g. "2" for 08:30 AM).
    if (numeric >= 1 && numeric <= 20) {
        return numeric - 1;
    }

    return numeric;
}

function buildChartTooltip(timestamps) {
    const labels = buildGraphCategoryLabels(timestamps);

    return {
        shared: true,
        intersect: false,
        x: {
            show: true,
            formatter: function (value, opts) {
                const index = resolveChartPointIndex(value, opts);

                if (index != null && timestamps?.[index]) {
                    return formatTrendTooltipLabel(timestamps[index]);
                }

                if (index != null && labels[index]) {
                    return labels[index];
                }

                const categories = opts?.w?.config?.xaxis?.categories;

                if (index != null && categories?.[index]) {
                    return categories[index];
                }

                if (typeof value === "string" && value.includes(":")) {
                    return formatTrendTooltipLabel(value);
                }

                return value ?? "";
            },
        },
    };
}

function normalizeTrendTimestamp(value) {
    if (value == null) {
        return "";
    }

    let raw = String(value).trim();

    if (raw.includes("T")) {
        raw = raw.replace("T", " ").replace(/\.\d+Z?$/, "").replace("Z", "");
    }

    return raw;
}

function formatTrendTooltipLabel(stamp) {
    const raw = normalizeTrendTimestamp(stamp);
    const [datePart] = raw.split(" ");

    if (!datePart) {
        return raw;
    }

    const [year, month, day] = datePart.split("-").map((part) => parseInt(part, 10));

    if ([year, month, day].some((part) => Number.isNaN(part))) {
        return `${raw} at ${formatSchedulerLabel(raw)}`;
    }

    const monthName = new Date(year, month - 1, day).toLocaleString("en-US", {
        month: "long",
    });

    return `${monthName} ${day}, ${year} at ${formatSchedulerLabel(raw)}`;
}

/** Category labels for trend charts (date + scheduler wall-clock time). */
function buildTrendCategories(timestamps) {
    return (timestamps || []).map((stamp) => {
        const raw = normalizeTrendTimestamp(stamp);
        const [datePart] = raw.split(" ");

        if (!datePart) {
            return formatSchedulerLabel(raw);
        }

        const [, month, day] = datePart.split("-");

        return `${month}/${day} ${formatSchedulerLabel(raw)}`;
    });
}

function buildTrendCategoryXaxis(timestamps) {
    return {
        type: "category",
        categories: buildTrendCategories(timestamps),
        tickPlacement: "on",
        labels: {
            rotate: -45,
            rotateAlways: false,
            hideOverlappingLabels: false,
            trim: true,
            style: {
                colors: "#9fa7bc",
                fontSize: "10px",
            },
        },
        axisTicks: {
            show: true,
        },
        tooltip: {
            enabled: false,
        },
    };
}

function roundTrendValue(value) {
    if (value == null || Number.isNaN(value)) {
        return null;
    }

    return Math.round(Number(value) * 10) / 10;
}

function formatTrendAxisValue(value) {
    if (value == null || Number.isNaN(value)) {
        return "";
    }

    const rounded = roundTrendValue(value);

    return Number.isInteger(rounded)
        ? String(rounded)
        : rounded.toFixed(1);
}

function trendValueSuffix(rawLabel = "") {
    return String(rawLabel).toLowerCase().includes("temp") ? " °C" : " %";
}

function buildTrendYaxis(min, max) {
    return {
        min,
        max,
        tickAmount: 6,
        decimalsInFloat: 1,
        labels: {
            style: {
                colors: "#9fa7bc",
                fontSize: "12px",
            },
            formatter: function (value) {
                return formatTrendAxisValue(value);
            },
        },
    };
}

function buildTrendTooltip(timestamps, rawLabel = "") {
    const labels = (timestamps || []).map((stamp) =>
        formatTrendTooltipLabel(stamp)
    );
    const suffix = trendValueSuffix(rawLabel);

    return {
        shared: true,
        intersect: false,
        y: {
            formatter: function (value) {
                return formatTrendAxisValue(value) + suffix;
            },
        },
        x: {
            show: true,
            formatter: function (value, opts) {
                const index = resolveChartPointIndex(value, opts);

                if (index != null && labels[index]) {
                    return labels[index];
                }

                if (index != null && timestamps?.[index]) {
                    return formatTrendTooltipLabel(timestamps[index]);
                }

                const categories = opts?.w?.config?.xaxis?.categories;

                if (index != null && categories?.[index]) {
                    return categories[index];
                }

                return value ?? "";
            },
        },
    };
}

function applyTrendChart(chart, rawData, trendLine, rawLabel) {
    const timestamps = (rawData || []).map((item) =>
        normalizeTrendTimestamp(item.created_at)
    );
    const rawSeries = toNumericSeries((rawData || []).map((item) => item.data)).map(
        roundTrendValue
    );
    const trendSeries = toNumericSeries((trendLine || []).map((item) => item.y)).map(
        roundTrendValue
    );
    const numericValues = [...rawSeries, ...trendSeries].filter(
        (value) => value != null && !Number.isNaN(value)
    );

    const yMin =
        numericValues.length > 0
            ? roundTrendValue(Math.min(...numericValues, 0) - 1)
            : -1;
    const yMax =
        numericValues.length > 0
            ? roundTrendValue(Math.max(...numericValues, 0) + 1)
            : 1;

    chart.updateOptions({
        xaxis: buildTrendCategoryXaxis(timestamps),
        tooltip: buildTrendTooltip(timestamps, rawLabel),
        yaxis: buildTrendYaxis(yMin, yMax),
    });

    chart.updateSeries([
        {
            name: rawLabel,
            data: rawSeries,
        },
        {
            name: "Trend Line",
            data: trendSeries,
        },
    ]);
}

window.CliChartLabels = {
    normalizeTrendTimestamp,
    formatSchedulerLabel,
    formatTrendTooltipLabel,
    formatTrendAxisValue,
    buildTrendCategories,
    buildTrendCategoryXaxis,
    buildTrendYaxis,
    buildTrendTooltip,
    applyTrendChart,
    toNumericSeries,
    buildGraphCategoryXaxis,
    setGraphDateRange,
};

function buildCpuChartSeries(utilizationValues, temperatureValues) {
    return [
        {
            name: "Utilization",
            data: toNumericSeries(utilizationValues),
        },
        {
            name: "Temperature",
            data: toNumericSeries(temperatureValues),
        },
    ];
}

function buildGpuChartSeries(usageValues, temperatureValues) {
    return [
        {
            name: "Usage",
            data: toNumericSeries(usageValues),
        },
        {
            name: "Temperature",
            data: toNumericSeries(temperatureValues),
        },
    ];
}

function buildRamChartSeries(usageValues) {
    return [
        {
            name: "RAM Usage",
            data: toNumericSeries(usageValues),
        },
    ];
}

if (currentDeviceId !== null) {
    // CPU
    var utilizationData = [];
    var temperatureData = [];
    var timestampss = [];
    // RAM
    var ramUsage = [];
    var ramTimestamps = [];

    // GPU
    var gpuUsage = [];
    var gpuTemp = [];
    var gpuTimestamps = [];

    var chart;
    var chart1;
    var chart2;

    function applyCpuGraphPayload(payload) {
        utilizationData = payload?.util ?? [];
        temperatureData = payload?.temp ?? [];
        timestampss = payload?.timestamps ?? [];
        setGraphDateRange(payload?.startDate, payload?.endDate);

        if (!chart) {
            window.__pendingCpuGraphPayload = payload;
            return;
        }

        refreshCpuChart();
    }

    function applyRamGraphPayload(payload) {
        ramUsage = payload?.usage ?? [];
        ramTimestamps = payload?.timestamps ?? [];
        setGraphDateRange(payload?.startDate, payload?.endDate);

        if (!chart1) {
            window.__pendingRamGraphPayload = payload;
            return;
        }

        refreshRamChart();
    }

    function applyGpuGraphPayload(payload) {
        gpuUsage = payload?.usage ?? [];
        gpuTemp = payload?.temp ?? [];
        gpuTimestamps = payload?.timestamps ?? [];
        setGraphDateRange(payload?.startDate, payload?.endDate);

        if (!chart2) {
            window.__pendingGpuGraphPayload = payload;
            return;
        }

        refreshGpuChart();
    }

    window.updateCpuGraphFromLivewire = applyCpuGraphPayload;
    window.updateRamGraphFromLivewire = applyRamGraphPayload;
    window.updateGpuGraphFromLivewire = applyGpuGraphPayload;

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

    var borderColor = KTThemeMode.getMode() === "light" ? "#f1f3f7" : "#25282f";
    KTThemeMode.on("kt.thememode.change", () => {
        borderColor = KTThemeMode.getMode() === "light" ? "#f1f3f7" : "#25282f";
        [chart, chart1, chart2].forEach((ch) =>
            ch.updateOptions({ grid: { borderColor } })
        );
    });
    var options = {
        series: buildCpuChartSeries(utilizationData, temperatureData),
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
        xaxis: buildGraphCategoryXaxis(timestampss),
        yaxis: [
            {
                tickAmount: 8,
                min: 0,
                max: 100,
                labels: {
                    style: {
                        colors: "#9fa7bc",
                        fontSize: "12px",
                    },
                    formatter: function (value) {
                        return value + " %";
                    },
                },
            },
            {
                opposite: true,
                tickAmount: 8,
                min: 0,
                max: 100,
                labels: {
                    style: {
                        colors: "#9fa7bc",
                        fontSize: "12px",
                    },
                    formatter: function (value) {
                        return value + " °C";
                    },
                },
            },
        ],
        grid: {
            borderColor: borderColor,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        tooltip: buildChartTooltip(timestampss),
    };
    var options1 = {
        series: buildRamChartSeries(ramUsage),
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
        xaxis: buildGraphCategoryXaxis(ramTimestamps),
        yaxis: {
            tickAmount: 8,
            max: 100,
            min: 0,
            labels: {
                style: {
                    colors: "#9fa7bc",
                    fontSize: "12px",
                },
                formatter: function (value) {
                    return value + " %";
                },
            },
        },
        grid: {
            borderColor: borderColor,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        tooltip: buildChartTooltip(ramTimestamps),
    };
    var options2 = {
        series: buildGpuChartSeries(gpuUsage, gpuTemp),
        chart: {
            fontFamily: "inherit",
            type: "area",
            height: 350,
            toolbar: {
                show: true,
            },
        },
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
        xaxis: buildGraphCategoryXaxis(gpuTimestamps),
        yaxis: [
            {
                min: 0,
                max: 100,
                tickAmount: 5, // Adjust this to fit your design
                labels: {
                    style: {
                        colors: "#9fa7bc",
                        fontSize: "12px",
                    },
                    formatter: function (value) {
                        return value + " %"; // Format for Usage
                    },
                },
            },
            {
                opposite: true,
                min: 0, // Minimum set to 0°C
                max: 100, // Maximum set to 100°C
                tickAmount: 5, // Adjust this to fit your design
                labels: {
                    style: {
                        colors: "#9fa7bc",
                        fontSize: "12px",
                    },
                    formatter: function (value) {
                        return value + " °C"; // Format for Temperature
                    },
                },
            },
        ],
        grid: {
            borderColor: "#f1f3f7",
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        tooltip: buildChartTooltip(gpuTimestamps),
    };

    function refreshCpuChart() {
        chart.updateOptions({
            xaxis: buildGraphCategoryXaxis(timestampss),
            tooltip: buildChartTooltip(timestampss),
        });
        chart.updateSeries(buildCpuChartSeries(utilizationData, temperatureData));
    }

    function refreshGpuChart() {
        chart2.updateOptions({
            xaxis: buildGraphCategoryXaxis(gpuTimestamps),
            tooltip: buildChartTooltip(gpuTimestamps),
        });
        chart2.updateSeries(buildGpuChartSeries(gpuUsage, gpuTemp));
    }

    function refreshRamChart() {
        chart1.updateOptions({
            xaxis: buildGraphCategoryXaxis(ramTimestamps),
            tooltip: buildChartTooltip(ramTimestamps),
        });
        chart1.updateSeries(buildRamChartSeries(ramUsage));
    }

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
        window.Echo.leave("cpu-graph." + currentDeviceId);
        window.Echo.leave("gpu-graph." + currentDeviceId);
        window.Echo.leave("ram-graph." + currentDeviceId);
    });

    // CPU
    chart = new ApexCharts(
        document.querySelector("#cpu_temp_utilGraph"),
        options
    );
    chart.render();
    if (window.__pendingCpuGraphPayload) {
        applyCpuGraphPayload(window.__pendingCpuGraphPayload);
        window.__pendingCpuGraphPayload = null;
    }
    // RAM
    chart1 = new ApexCharts(
        document.querySelector("#ram_usage_graph"),
        options1
    );

    chart1.render();
    if (window.__pendingRamGraphPayload) {
        applyRamGraphPayload(window.__pendingRamGraphPayload);
        window.__pendingRamGraphPayload = null;
    }
    // GPU
    chart2 = new ApexCharts(
        document.querySelector("#gpu_usage_graph"),
        options2
    );
    chart2.render();
    if (window.__pendingGpuGraphPayload) {
        applyGpuGraphPayload(window.__pendingGpuGraphPayload);
        window.__pendingGpuGraphPayload = null;
    }
    document.addEventListener('livewire:navigated', () => {
        window.Echo.private("cpu-graph." + currentDeviceId).listen(
            ".cpu.graph.update",
            (e) => {
                utilizationData.push(e.util);
                temperatureData.push(e.temp);
                timestampss.push(formatLabTimestamp());

                refreshCpuChart();

                const maxDataPoints = 20;
                if (utilizationData.length > maxDataPoints) {
                    utilizationData.shift();
                    temperatureData.shift();
                    timestampss.shift();
                }
                Livewire.dispatch('update-sidebar')
            }
        );
        window.Echo.private("gpu-graph." + currentDeviceId).listen(
            ".gpu.graph.update",
            (e) => {
                gpuUsage.push(e.usage);
                gpuTemp.push(e.temp);
                gpuTimestamps.push(formatLabTimestamp());

                refreshGpuChart();

                const maxDataPoints = 20;
                if (gpuUsage.length > maxDataPoints) {
                    gpuUsage.shift();
                    gpuTemp.shift();
                    gpuTimestamps.shift();
                }
                Livewire.dispatch('update-sidebar')
            }
        );
        window.Echo.private("ram-graph." + currentDeviceId).listen(
            ".ram.graph.update",
            (e) => {
                ramUsage.push(e.usage);
                ramTimestamps.push(formatLabTimestamp());

                refreshRamChart();

                const maxDataPoints = 20;
                if (ramUsage.length > maxDataPoints) {
                    ramUsage.shift();
                    ramTimestamps.shift();
                }
                Livewire.dispatch('update-sidebar')
            }
        );
    });
    Livewire.on("openModalTrend", () => {
        $("#generate_trend_modal").modal("show");
        chart4.updateSeries([
            {
                data: [],
            },
            {
                data: [],
            },
        ]);
    });

    // Initialize the trend analysis chart
    var chart4 = new ApexCharts(document.querySelector("#trend_graph"), {
        chart: {
            fontFamily: "inherit",
            type: "line",
            height: 350,
            toolbar: {
                show: true,
            },
        },
        series: [
            {
                data: [],
            },
            {
                name: "Trend Line",
                data: [],
            },
        ],
        xaxis: buildTrendCategoryXaxis([]),
        stroke: {
            curve: ["smooth", "straight"],
            show: true,
            width: 3,
        },
        dataLabels: {
            enabled: false,
        },
        grid: {
            borderColor: borderColor,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        yaxis: buildTrendYaxis(-1, 1),
        tooltip: buildTrendTooltip([]),
    });

    chart4.render();

    Livewire.on("refreshCharts", () => {
        applyTrendChart(
            chart4,
            event.detail.raw_data || [],
            event.detail.trend_line || [],
            event.detail.raw_data_label || "No Data"
        );
    });
    
    
    Livewire.on("closeModalTrend", () => {
        Swal.fire(
            "Saved!",
            "The Trend Analysis Data has been saved",
            "success"
        );
        $("#generate_trend_modal").modal("hide");
    });
}
