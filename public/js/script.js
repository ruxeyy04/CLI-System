toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: "toastr-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
};

Livewire.on("profile-updated", () => {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: "Profile details have been successfully updated",
    });
    $(".image-input").removeClass("image-input-changed");
    const imageInput = document.querySelector(".image-input");
    const imageWrapper = imageInput.querySelector(".image-input-wrapper");
});
Livewire.on("saved-user", () => {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: "New account is added successfully",
    });
});
Livewire.on("discardImageCol", () => {
    const imageInput = document.querySelector(".image-input");
    const imageWrapper = imageInput.querySelector(".image-input-wrapper");

    imageInput.style.backgroundImage = `url('{{ asset('storage/profile/default.jpg') }}')`;
    imageWrapper.style.backgroundImage = "none";
    $(".image-input").addClass("image-input-empty");
});
Livewire.on("backToDefault", () => {
    const imageInput = document.querySelector(".image-input");
    const imageWrapper = imageInput.querySelector(".image-input-wrapper");

    imageWrapper.style.backgroundImage =
        `url('` +
        "{{ asset('storage/profile' . '/' . Auth::user()->id . '/' . Auth::user()->profileimg) }}" +
        `')`;
    $(".image-input").removeClass("image-input-empty");
});

Livewire.on("confirm-reset-password", (e) => {
    const userId = event.detail.userId;
    Swal.fire({
        title: "Are you sure?",
        text: "This will reset the password for this user!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, reset it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("reset-password", { userId: userId });
            Livewire.on("reset-password-success", () => {
                Swal.fire("Reset!", "The password has been reset.", "success");
            });
        }
    });
});
Livewire.on("confirm-delete-user", () => {
    const userId = event.detail.userId;
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the account of the user!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("delete-user", { userId: userId });
            Livewire.on("delete-user-alert", (e) => {
                const status = event.detail.status;
                if (status == "success") {
                    Swal.fire(
                        "Deleted!",
                        "The user account has been deleted.",
                        "success"
                    );
                } else {
                    Swal.fire(
                        "Error!",
                        "The account deletion has been failed",
                        "error"
                    );
                }
            });
        }
    });
});

Livewire.on("openEditUserModal", () => {
    const userId = event.detail.userId;
    Livewire.dispatch("open-user-modal", { userId: userId });
    Livewire.on("open-modal", () => {
        $("#edituser_modal").modal("show");
        Livewire.on("updated-user", () => {
            $("#edituser_modal").modal("hide");
            Swal.fire({
                icon: "success",
                title: "Success",
                text: "User account is updated successfully",
            });
        });
    });
});

Livewire.on("add-laboratory-modal", () => {
    $("#addlab_modal").modal("show");
    Livewire.on("add-laboratory-success", () => {
        $("#addlab_modal").modal("hide");
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Laboratory is created successfully",
        });
    });
});
Livewire.on("delete-lab-confirmation", () => {
    const lab_id = event.detail.lab_id;
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the laboratory",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("delete-laboratory", { lab_id: lab_id });
            Livewire.on("delete-laboratory-alert", (e) => {
                const status = event.detail.status;
                if (status == "success") {
                    Swal.fire(
                        "Deleted!",
                        "The laboratory has been deleted.",
                        "success"
                    );
                } else {
                    Swal.fire(
                        "Error!",
                        "The laboratory deletion has been failed",
                        "error"
                    );
                }
            });
        }
    });
});

Livewire.on("add-user-modal", () => {
    $("#adduser_modal").modal("show");
});

Livewire.on("editlab_modal", () => {
    const lab_id = event.detail.lab_id;

    Livewire.dispatch("edit_lab", { lab_id: lab_id });

    Livewire.on("edit_lab_done", () => {
        $("#edit_lab_modal").modal("show");
    });
    Livewire.on("update-laboratory-success", () => {
        $("#edit_lab_modal").modal("hide");
        Swal.fire("Success!", "The laboratory has been updated.", "success");
    });
});
Livewire.on("viewlab_modal", () => {
    const lab_id = event.detail.lab_id;
    Livewire.dispatch("view_lab", { lab_id: lab_id });
    Livewire.on("view_lab_done", () => {
        $("#viewlab_modal").modal("show");
    });
});

Livewire.on("add-device-modal", () => {
    $("#add_device_modal").modal("show");
    Livewire.on("add-device-success", () => {
        $("#add_device_modal").modal("hide");
        Swal.fire("Success!", "The computer device has been added.", "success");
    });
});

$(".lab_select").change(function (e) {
    e.preventDefault();
    Livewire.dispatch("lab_select", { lab_id: $(this).val() });
});

Livewire.on("deleteDeviceConfirmation", () => {
    const dev_id = event.detail.dev_id;
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the computer device",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("delete-device", { dev_id: dev_id });
            Livewire.on("delete-device-alert", (e) => {
                const status = event.detail.status;
                if (status == "success") {
                    Swal.fire(
                        "Deleted!",
                        "The computer device has been deleted.",
                        "success"
                    );
                } else {
                    Swal.fire(
                        "Error!",
                        "The computer device deletion has been failed",
                        "error"
                    );
                }
            });
        }
    });
});

Livewire.on("openEditDeviceModal", () => {
    const dev_id = event.detail.dev_id;

    Livewire.dispatch("edit_device", { dev_id: dev_id });

    Livewire.on("edit_device_done", () => {
        const select2Value = event.detail.select2;
        $(".lab_select").val(select2Value).trigger("change"); // Update and trigger change
        $("#edit_device_modal").modal("show");
    });
    Livewire.on("update-device-success", () => {
        $("#edit_device_modal").modal("hide");
        Swal.fire(
            "Success!",
            "The computer device has been updated.",
            "success"
        );
    });
});

Livewire.on("removePatchConfirmation", () => {
    const dev_id = event.detail.dev_id;
    Swal.fire({
        title: "Are you sure?",
        text: "This will remove the patch of the device",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, remove it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("remove-patch", { dev_id: dev_id });
            Livewire.on("remove-patch-alert", (e) => {
                const status = event.detail.status;
                if (status == "success") {
                    Swal.fire(
                        "Removed Patch!",
                        "The patch has been removed from the device",
                        "success"
                    );
                } else {
                    Swal.fire(
                        "Error!",
                        "The patch removal has been failed",
                        "error"
                    );
                }
            });
        }
    });
});
Livewire.on("update-data-modal-input", () => {
    let id = event.detail.id;
    let type = event.detail.type;
    console.log(id, type);
    Livewire.on("open-update-modal", () => {
        $("#updateinput_modal").modal("show");
    });
    Livewire.on("close-modal", () => {
        $("#updateinput_modal").modal("hide");
        Swal.fire(
            "Success!",
            "The information is successfully saved",
            "success"
        );
    });
    Livewire.on("error", () => {
        let msg = event.detail.message;
        Swal.fire("Error!", msg, "error");
    });
});

Livewire.on("openSavedTrendModal", () => {
    $("#view_trend_logs").modal("show");
});
Livewire.on("delete-trend-confirmation", () => {
    const id = event.detail.id;
    Swal.fire({
        title: "Are you sure?",
        text: "This will delete the trend log",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch("delete-trend", { id: id });
            Livewire.on("delete-trend-alert", (e) => {
                const status = event.detail.status;
                if (status == "success") {
                    Swal.fire(
                        "Deleted!",
                        "The trend log has been deleted.",
                        "success"
                    );
                } else {
                    Swal.fire(
                        "Error!",
                        "The trend log deletion has been failed",
                        "error"
                    );
                }
            });
        }
    });
});

Livewire.on("viewTrendChart", () => {
    const rawData = event.detail.raw_data;
    const trend_line = event.detail.trend_line;
    const raw_label = event.detail.raw_data_label;
    // Initialize the chart
    var chart = new ApexCharts(document.querySelector("#view_trend_graph"), {
        chart: {
            fontFamily: "inherit",
            height: 500,
            toolbar: {
                show: true,
            },
        },
        series: [
            {
                data: []
            },
            {
                name: 'Trend Line',
                data: []
            }
        ],
        xaxis: {
            type: 'datetime',
            labels: {
                formatter: function (value) {
                    return new Date(value).toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true,
                    });
                }
            }
        },
        stroke: {
            curve: ["smooth", "straight"],
            show: true,
            width: 3,
        },
        labels: {
            rotate: 0,
            rotateAlways: true,
            style: {
                colors: "#f1f3f7",
                fontSize: "12px",
            },
        },
        dataLabels: {
            enabled: false,
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
            borderColor: borderColor,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
                tooltip: {
            x: {
                formatter: function (val) {
                    return new Date(val).toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true,
                    });
                }
            }
        }
    });

    chart.render();

    const cpuTemperatureData = rawData.map(item => ({
        x: new Date(item.created_at).getTime(),
        y: parseFloat(item.data).toFixed(2)
    }));

    const trendLineData = trend_line.map(item => ({
        x: new Date(item.x).getTime(),
        y: parseFloat(item.y).toFixed(2)
    }));
    console.log(cpuTemperatureData, trendLineData)
    chart.updateSeries([
        {
            name: raw_label,
            data: cpuTemperatureData
        },
        {
            name: 'Trend Line',
            data: trendLineData
        }
    ]);
});
