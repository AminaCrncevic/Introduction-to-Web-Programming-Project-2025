var AccountService = {
    init: function () {
        AccountService.loadUserInfo();

        $("#update-form").on("submit", function (e) {
            e.preventDefault();
            AccountService.updateUserInfo();
        });

        $("#delete-account").on("click", function () {
            AccountService.deleteAccount();
        });

        $("#logout").on("click", function () {
            UserService.logout();
        });
    },

    loadUserInfo: function () {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;

        if (!user) {
            alert("User not found")
            return;
        }

        $("#first-name").text(user.FirstName);
        $("#last-name").text(user.LastName);
        $("#email").text(user.email);
        $("#user-type").text(user.UserType);
    },

    updateUserInfo: function () {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;

        const firstName = $("#new-first-name").val().trim();
        const lastName = $("#new-last-name").val().trim();

        if (!firstName || !lastName) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Both fields are required.'
            });
            return;
        }

        const data = {
            FirstName: firstName,
            LastName: lastName
        };

        $.ajax({
            url: Constants.PROJECT_BASE_URL + "user/" + user.id,
            type: "PATCH",
            headers: {
                "Authentication": token
            },
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            success: function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Update Successful',
                    text: 'Your account information has been updated.',
                    timer: 2000,
                    showConfirmButton: false
                });
                //AccountService.loadUserInfo();
                 $("#first-name").text(firstName);
                 $("#last-name").text(lastName);
                $("#new-first-name").val("");
                $("#new-last-name").val("");
            },
            error: function (xhr) {
                const message = xhr.responseJSON?.error || "Update failed.";
                toastr.error(message);
            }
        });
    },

    deleteAccount: function () {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;

        Swal.fire({
            title: 'Are you sure?',
            text: "Your account will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: Constants.PROJECT_BASE_URL + "user/" + user.id,
                    type: "DELETE",
                    headers: {
                        "Authentication": token
                    },
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Your account has been deleted.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        UserService.logout();
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.error || "Deletion failed.";
                        toastr.error(message);
                    }
                });
            }
        });
    }
};

// Initialize when the DOM is ready
$(document).ready(function () {
    if (window.location.hash === "#account") {
        AccountService.init();
    }
});
