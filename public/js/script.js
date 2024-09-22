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
                icon: 'success',
                title: 'Success',
                text: 'User account is updated successfully',
            });
        });
    });
});

Livewire.on('add-laboratory-modal', () => {
    $("#addlab_modal").modal("show");
    Livewire.on("add-laboratory-success", () => {
        $("#addlab_modal").modal("hide");
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Laboratory is created successfully',
        });
    });
})
Livewire.on('add-user-modal', () => {
    $("#adduser_modal").modal("show");

})