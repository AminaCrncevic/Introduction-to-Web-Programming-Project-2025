
const AdminDashboard = {

  init: function () {
    this.loadDashboardData();
    this.handleManualPaymentUpdate();
    this.initManageUsersForm();  
    this.initAddProductForm(); 
    this.initManageProductsForm();
  },


loadDashboardData: function () {
    RestClient.get("admin/dashboard", function (data) {
      $("#pending-orders").text(data.pendingOrders ?? 0);
      $("#completed-payments").text(`$${data.totalRevenue ?? 0}/-`);
      $("#total-orders").text(data.totalOrders ?? 0);
      $("#total-products").text(data.totalProducts ?? 0);
      $("#normal-users").text(data.normalUsers ?? 0);
      $("#admin-users").text(data.adminUsers ?? 0);
      $("#total-accounts").text(data.totalUsers ?? 0);
    });
  },



 handleManualPaymentUpdate: function () {
    $('#manual-update-form').submit(function (e) {
      e.preventDefault();

      const orderId = $('#order-id').val();
      const paymentStatus = $('#update-payment').val();
      const userToken = localStorage.getItem('user_token');

      if (!userToken) {
        Swal.fire({
          icon: 'error',
          title: 'Authentication Error',
          text: 'Authentication token missing.'
        });
        return;
      }

       $.blockUI({ message: '<h3>Fetching order info...</h3>' });

      $.ajax({
        url: Constants.PROJECT_BASE_URL + "orders/single/" + orderId,
        type: 'GET',
        headers: { 'Authentication': userToken },
        success: function (order) {
          const userId = order.Users_UserID;

          if (paymentStatus === 'completed') {
               $.blockUI({ message: '<h3>Completing order...</h3>' });
            $.ajax({
              url: Constants.PROJECT_BASE_URL + "orders/complete/" + orderId + "/" + userId,
              type: 'PUT',
              headers: { 'Authentication': userToken },
              success: function () {
                $.unblockUI();
                Swal.fire({
                  icon: 'success',
                  title: 'Order Completed',
                  text: 'Order marked as completed successfully.',
                  timer: 2000,
                  showConfirmButton: false
                });
              },
              error: function (err) {
                 $.unblockUI();
                const errorMessage = err.responseJSON?.error || "Something went wrong during completion.";
                Swal.fire({ icon: 'error', title: 'Error', text: errorMessage });
              }
            });
          } else {
             $.unblockUI();
            Swal.fire({
              icon: 'info',
              title: 'Pending Payment',
              text: 'Order payment set to pending. No action taken.',
              timer: 2000,
              showConfirmButton: false
            });
          }
        },
        error: function (err) {
            $.unblockUI();
          const errorMessage = err.responseJSON?.error || "Failed to fetch order.";
          Swal.fire({ icon: 'error', title: 'Error', text: errorMessage });
        }
      });
    });
  },



initManageUsersForm: function () {
  const userIdInput = $('#user-id');
  const fetchBtn = $('#fetch-user-btn');
  const firstNameInput = $('#first-name');
  const lastNameInput = $('#last-name');
  const userTypeSelect = $('#user-type1');
  const updateBtn = $('#update-user-btn');
  const deleteBtn = $('#delete-user-btn');

  function toggleFormInputs(enabled) {
    firstNameInput.prop('disabled', !enabled);
    lastNameInput.prop('disabled', !enabled);
    userTypeSelect.prop('disabled', !enabled);
    updateBtn.prop('disabled', !enabled);
    deleteBtn.prop('disabled', !enabled);
  }

  toggleFormInputs(false);

  fetchBtn.on('click', function () {
    const userId = userIdInput.val().trim();
    if (!userId) {
      Swal.fire('Error', 'Please enter a valid User ID.', 'error');
      return;
    }

    const userToken = localStorage.getItem('user_token');
    if (!userToken) {
      Swal.fire('Error', 'Authentication token missing.', 'error');
      return;
    }

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}user/${userId}`,
      type: 'GET',
      headers: { 'Authentication': userToken },
      success: function (user) {
      
        firstNameInput.val(user.FirstName || '');
        lastNameInput.val(user.LastName || '');

        let userType = (user.UserType || '').toString().toLowerCase().trim();
        console.log('Fetched user UserType:', userType);

        userTypeSelect.val(userType);
        console.log('Set select value to:', userType);
        console.log('Select current value:', userTypeSelect.val());
        console.log('Select options:', userTypeSelect.find('option').map((_, opt) => opt.value).get());

        toggleFormInputs(true);
      },
      error: function (err) {
        const msg = err.responseJSON?.error || 'User not found or access denied.';
        Swal.fire('Error', msg, 'error');
        toggleFormInputs(false);
        firstNameInput.val('');
        lastNameInput.val('');
        userTypeSelect.val('');
      }
    });
  });

  // VALIDATE 
  $('#manage-users-form').validate({
    rules: {
      "first-name": {
        required: true,
        minlength: 2
      },
      "last-name": {
        required: true,
        minlength: 2
      },
      "user-type": {
        required: true
      }
    },
    messages: {
      "first-name": {
        required: "First name is required",
        minlength: "At least 2 characters"
      },
      "last-name": {
        required: "Last name is required",
        minlength: "At least 2 characters"
      },
      "user-type": {
        required: "Please select a user type"
      }
    },
    submitHandler: function () {
      const userId = userIdInput.val().trim();
      const userToken = localStorage.getItem('user_token');

      if (!userId || !userToken) {
        Swal.fire('Error', 'Missing User ID or Token', 'error');
        return;
      }

      const data = {
        FirstName: firstNameInput.val().trim(),
        LastName: lastNameInput.val().trim(),
        UserType: userTypeSelect.val()
      };

      $.blockUI({ message: '<h3>Updating user...</h3>' });

      $.ajax({
        url: `${Constants.PROJECT_BASE_URL}user/${userId}`,
        type: 'PATCH',
        headers: {
          'Authentication': userToken,
          'Content-Type': 'application/json'
        },
        data: JSON.stringify(data),
        success: function () {
          $.unblockUI();
          toastr.success("User updated successfully");
        },
        error: function (err) {
          $.unblockUI();
          const msg = err.responseJSON?.error || 'Failed to update user.';
          toastr.error(msg);
        }
      });
    }
  });

  // DELETE USER 
  deleteBtn.on('click', function () {
    const userId = userIdInput.val().trim();

    if (!userId) {
      Swal.fire('Error', 'Please enter a valid User ID.', 'error');
      return;
    }

    const userToken = localStorage.getItem('user_token');
    if (!userToken) {
      Swal.fire('Error', 'Authentication token missing.', 'error');
      return;
    }

    Swal.fire({
      title: 'Are you sure?',
      text: "This action will permanently delete the user.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.blockUI({ message: '<h3>Deleting user...</h3>' });

        $.ajax({
          url: `${Constants.PROJECT_BASE_URL}user/${userId}`,
          type: 'DELETE',
          headers: { 'Authentication': userToken },
          success: function () {
            $.unblockUI();
            toastr.success('User deleted successfully');
            userIdInput.val('');
            firstNameInput.val('');
            lastNameInput.val('');
            userTypeSelect.val('');
            toggleFormInputs(false);
          },
          error: function (err) {
            $.unblockUI();
            const msg = err.responseJSON?.error || 'Failed to delete user.';
            Swal.fire('Error', msg, 'error');
          }
        });
      }
    });
  });
},


initAddProductForm: function () {
  const form = document.getElementById("addProductForm");
  if (!form) return;

  
  $("#addProductForm").validate({
    rules: {
      ProductName: { required: true },
      ProductDescription: { required: true },
      ProductPrice: { required: true, number: true, min: 0 },
      ProductImage: { required: true, url: true }
    },
    messages: {
      ProductName: "Please enter the flower name.",
      ProductDescription: "Please provide a description.",
      ProductPrice: {
        required: "Please enter the flower price.",
        number: "Price must be a number.",
        min: "Price cannot be negative."
      },
      ProductImage: {
        required: "Please enter an image URL.",
        url: "Please enter a valid URL."
      }
    },
    submitHandler: (form) => {
      const userToken = localStorage.getItem("user_token");
      if (!userToken) {
        Swal.fire("Error", "You must be logged in to add a product.", "error");
        return;
      }

      const data = {
        ProductName: form.ProductName.value.trim(),
        ProductDescription: form.ProductDescription.value.trim(),
        ProductPrice: parseFloat(form.ProductPrice.value),
        ProductImage: form.ProductImage.value.trim()
      };

      $.blockUI({ message: '<h3>Adding flower...</h3>' });

      fetch(Constants.PROJECT_BASE_URL + "product", {
        method: "POST",
        headers: {
          'Authentication': userToken,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
        .then((res) => {
          if (!res.ok) throw new Error("Failed to add product");
          return res.json();
        })
        .then((product) => {
          toastr.success("Product added successfully!");
          $.unblockUI();

        
          if (typeof this.addProductToDOM === 'function') {
            this.addProductToDOM(product);
          }

          form.reset();
        })
        .catch((error) => {
          console.error("Error adding product:", error);
          toastr.error("Failed to add product.");
          $.unblockUI();
        });
    }
  });
},



initManageProductsForm: function () {
  const productIdInput = $('#product-id');
  const fetchBtn = $('#fetch-product-btn');
  const nameInput = $('#product-name');
  const descInput = $('#product-description');
  const priceInput = $('#product-price');
  const imageInput = $('#product-image');
  const updateBtn = $('#update-product-btn');
  const deleteBtn = $('#delete-product-btn');

  function toggleProductFormInputs(enabled) {
    nameInput.prop('disabled', !enabled);
    descInput.prop('disabled', !enabled);
    priceInput.prop('disabled', !enabled);
    imageInput.prop('disabled', !enabled);
    updateBtn.prop('disabled', !enabled);
    deleteBtn.prop('disabled', !enabled);
  }

  toggleProductFormInputs(false);

  fetchBtn.on('click', function () {
    const productId = productIdInput.val().trim();
    const token = localStorage.getItem('user_token');

    if (!productId) return Swal.fire('Error', 'Enter a product ID.', 'error');
    if (!token) return Swal.fire('Error', 'No auth token found.', 'error');

    $.blockUI({ message: '<h3>Fetching product...</h3>' });

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}product/${productId}`,
      type: 'GET',
      headers: { 'Authentication': token },
      success: function (product) {
        nameInput.val(product.ProductName);
        descInput.val(product.ProductDescription);
        priceInput.val(product.ProductPrice);
        imageInput.val(product.ProductImage);
        toggleProductFormInputs(true);
      },
      error: function (err) {
        const msg = err.responseJSON?.error || 'Product not found.';
        Swal.fire('Error', msg, 'error');
        toggleProductFormInputs(false);
      },
      complete: function () {
        $.unblockUI();
      }
    });
  });


  $('#manage-product-form').validate({
    rules: {
      productId: {
        required: true,
        digits: true,
        min: 1
      },
      ProductName: 'required',
      ProductDescription: 'required',
      ProductPrice: {
        required: true,
        number: true,
        min: 0.01
      },
      ProductImage: 'required'
    },
    messages: {
      productId: {
        required: 'Please enter product ID',
        digits: 'Product ID must be a positive integer',
        min: 'Product ID must be at least 1'
      },
      ProductName: 'Please enter product name',
      ProductDescription: 'Please enter product description',
      ProductPrice: {
        required: 'Please enter product price',
        number: 'Price must be a valid number',
        min: 'Price must be greater than zero'
      },
      ProductImage: 'Please enter product image URL'
    },
    submitHandler: function (form) {
      const productId = productIdInput.val().trim();
      const token = localStorage.getItem('user_token');
      if (!productId || !token) return;

      const data = {
        ProductName: nameInput.val().trim(),
        ProductDescription: descInput.val().trim(),
        ProductPrice: parseFloat(priceInput.val().trim()),
        ProductImage: imageInput.val().trim()
      };

      $.blockUI({ message: '<h3>Updating product...</h3>' });

      $.ajax({
        url: `${Constants.PROJECT_BASE_URL}product/${productId}`,
        type: 'PATCH',
        headers: {
          'Authentication': token,
          'Content-Type': 'application/json'
        },
        data: JSON.stringify(data),
        success: function () {
          Swal.fire('Success', 'Product updated successfully!', 'success');
        },
        error: function (err) {
          const msg = err.responseJSON?.error || 'Failed to update product.';
          Swal.fire('Error', msg, 'error');
        },
        complete: function () {
          $.unblockUI();
        }
      });
    }
  });

  deleteBtn.on('click', function () {
    const productId = productIdInput.val().trim();
    const token = localStorage.getItem('user_token');
    if (!productId || !token) return;

    Swal.fire({
      title: 'Are you sure?',
      text: 'This will permanently delete the product.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      confirmButtonColor: '#d33'
    }).then((result) => {
      if (result.isConfirmed) {
        $.blockUI({ message: '<h3>Deleting product...</h3>' });

        $.ajax({
          url: `${Constants.PROJECT_BASE_URL}product/${productId}`,
          type: 'DELETE',
          headers: { 'Authentication': token },
          success: function () {
            Swal.fire('Deleted!', 'Product has been deleted.', 'success');
            productIdInput.val('');
            nameInput.val('');
            descInput.val('');
            priceInput.val('');
            imageInput.val('');
            toggleProductFormInputs(false);
          },
          error: function (err) {
            const msg = err.responseJSON?.error || 'Failed to delete product.';
            Swal.fire('Error', msg, 'error');
          },
          complete: function () {
            $.unblockUI();
          }
        });
      }
    });
  });
}
  
};


// Initialize AdminDashboard after DOM is ready
$(document).ready(function () {
  AdminDashboard.init();
});
