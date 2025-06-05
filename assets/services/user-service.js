var UserService = {
    init: function () {
        const token = localStorage.getItem("user_token");

        if (token) {
            try {
                const parsed = Utils.parseJwt(token);
                if (parsed?.user?.UserType && (!window.location.hash || window.location.hash === "#loginpage1")) {
                    window.location.hash = "#home";
                    return;
                }
            } catch (e) {
                localStorage.removeItem("user_token");
            }
        }

        $("#login-form").validate({
            submitHandler: function (form) {
                const entity = Object.fromEntries(new FormData(form).entries());
                UserService.login(entity);
            },
        });

     
        $("#registration-form").validate({
    rules: {
      
       firstName: {
        required:true,
        minlength:2
       },
       lastName:{
        required:true,
        minlength:2
       },
      
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength: 8
        }
    },
    messages: {
        firstName: {
            required: "Please enter your first name",
            minlength: "First name must be at least 2 characters long!"
        },
        lastName: {
            required: "Please enter your last name",
            minlength: "Last name must be at least 2 characters long!"
        },
        email: {
            required: "Please enter your email",
            email: "Please enter a valid email"
        },
        password: {
            required: "Please provide a password",
            minlength: "Password must be at least 8 characters"
        }
    },
    submitHandler: function (form) {
        const entity = Object.fromEntries(new FormData(form).entries());
        UserService.register(entity);
    }
});

    },

    login: function (entity) {
         $.blockUI({ message: '<h4>Logging in...</h4>' }); 
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                $.unblockUI();
                localStorage.setItem("user_token", result.data.token);
                UserService.generateMenuItems(); // Inject nav and sections
           Swal.fire({
            icon: 'success',
            title: 'Welcome!',
            text: 'Login successful!',
            timer: 1000000,
            showConfirmButton: false
        });
            window.location.replace("index.html");
                
            },
            error: function (xhr) {
                 $.unblockUI();
                const message = xhr.responseJSON?.message || "Login failed.";
                toastr.error(message);
            },
        });
    },

    register: function (entity) {
        $.blockUI({ message: '<h4>Registering...</h4>' });
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function () {
                 $.unblockUI();
            Swal.fire({
                icon: 'success',
                title: 'Registration Complete!',
                text: 'You can now log in with your new account.',
                timer: 2000,
                showConfirmButton: false
            });
                setTimeout(() => {
                    window.location.hash = "#loginpage1";
                }, 100);
            },
            error: function (xhr) {
                 $.unblockUI();
                const message = xhr.responseJSON?.message || "Registration failed.";
                toastr.error(message);
            }
        });
    },

    logout: function () {
        localStorage.clear();
        window.location.replace("index.html");
    },

    generateMenuItems: function () {
        const token = localStorage.getItem("user_token");
        const user = Utils.parseJwt(token)?.user;
        

        if (!user?.UserType) {
            window.location.hash = "#loginpage1";
            return;
        }

 // === HEADER ICONS ===
    const headerIcons = document.getElementById("header-icons");
    if (user.UserType === Constants.USER_ROLE) {
        headerIcons.innerHTML = `
            <a id="shopping-cart-icon" href="#shoppingcart" class="fas fa-shopping-cart"></a>
            <div class="user-dropdown">
                <a href="#" class="fas fa-user" id="user-icon"></a>
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="#account">My Account</a>
                    <a href="#" id="logout-btn">Logout</a>
                </div>
            </div>
        `;
    } else {
        headerIcons.innerHTML = `
            <div class="user-dropdown">
                <a href="#" class="fas fa-user" id="user-icon"></a>
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="#adminDashboard">Dashboard</a>
                    <a href="#" id="logout-btn">Logout</a>
                </div>
            </div>
        `;
    }

    // Dropdown toggle logic
    const userIcon = document.getElementById("user-icon");
    const dropdownMenu = document.getElementById("dropdown-menu");

    userIcon?.addEventListener("click", (e) => {
        e.preventDefault();
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    });

    window.addEventListener("click", (e) => {
        if (!e.target.closest(".user-dropdown")) {
            dropdownMenu.style.display = "none";
        }
    });

    document.getElementById("logout-btn")?.addEventListener("click", (e) => {
        e.preventDefault();
        UserService.logout();
    });


    let nav = "", main = "";

        
    if (user.UserType === Constants.USER_ROLE) {
        $("#shopping-cart-icon").show();
    } else {
        $("#shopping-cart-icon").hide(); // Hide for admin
    }


        if (user.UserType === Constants.USER_ROLE) {
            nav = `
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#products">Shop</a></li>
                <li><a href="#wishlist">Wishlist</a></li>
                
                
            `;
            main = `
                <section id="home" data-load="home.html"></section>
                <section id="about" data-load="about.html"></section>
                <section id="products" data-load="products.html"></section>
                <section id="wishlist" data-load="wishlist.html"></section>
                <section id="orders" data-load="orders.html"></section>
                <section id="shoppingcart" data-load="shoppingcart.html"></section>
                <section id="checkoutpage" data-load="checkoutpage.html"></section>
                <section id="account" data-load="account.html"></section>
                <section id="productDetailPage" data-load="productDetailPage.html"></section>
            `;
        } else if (user.UserType === Constants.ADMIN_ROLE) {
            nav = `
                <li><a href="#adminDashboard">Dashboard</a></li>
                <li><a href="#adminOrders">Manage Orders</a></li>
                <li><a href="#adminUsers">Manage Users</a></li>
                <li><a href="#adminAddFlower">Add Flowers</a></li>
                <li><a href="#adminProducts">Update Flowers</a></li>
                
            `;
            main = `
                <section id="adminDashboard" data-load="adminDashboard.html"></section>
                <section id="adminOrders" data-load="adminOrders.html"></section>
                <section id="adminUsers" data-load="adminUsers.html"></section>
                <section id="adminAddFlower" data-load="adminAddFlower.html"></section>
                <section id="adminPage" data-load="adminPage.html"></section>
                <section id="adminProducts" data-load="adminProducts.html"></section>
            `;
        }

        $("#tabs").html(nav);
        $("#spapp").append(main);
    }
};
