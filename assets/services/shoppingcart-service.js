const CartService1 = {
  pendingOrderId: null,

  fetchPendingCartItems: function () {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (!user) {
      Swal.fire("Error", "Please log in to view your cart.", "error");
      return;
    }

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}orders/pending-items/${user.id}`,
      type: "GET",
      headers: {
        "Authentication": token
      },
      success: function (items) {
        if (items.length > 0) {
          CartService1.pendingOrderId = items[0].orderId;
        } else {
          CartService1.pendingOrderId = null;
        }
        CartService1.renderCart(items);
      },
      error: function (err) {
        console.error("Failed to load cart items", err);
        Swal.fire("Error", "Could not fetch cart items.", "error");
      }
    });
  },

  renderCart: function (items) {
    const cartContainer = $(".cart");
    const totalElement = $(".cart-total h3");
    cartContainer.empty();

    let total = 0;

    items.forEach(item => {
      const price = parseFloat(item.productPrice);
      const quantity = parseInt(item.quantity);
      const subtotal = parseFloat(item.subtotal);
      total += subtotal;

      const cartItem = $(`
        <div class="cart-item">
          <img src="${item.productImage}" alt="${item.productName}">
          <div class="item-details">
            <h3>${item.productName}</h3>
            <p>${price.toFixed(2)}$</p>
            <div class="quantity">Quantity: <span class="quantity-value">${quantity}</span></div>
            <button class="remove1-btn" data-order-item-id="${item.id}">Remove</button>
          </div>
        </div>
      `);

      cartContainer.append(cartItem);
    });

    totalElement.text(`Total: ${total.toFixed(2)}$`);

    $(".remove1-btn").off("click").on("click", function () {
      const orderItemId = $(this).data("order-item-id");
      CartService1.removeItemFromCart(orderItemId);
    });
  },

  removeItemFromCart: function (orderItemId) {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (!user) {
      Swal.fire("Error", "User not found.", "error");
      return;
    }

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}orders/remove-item/${orderItemId}/${user.id}`,
      type: "DELETE",
      headers: {
        "Authentication": token
      },
      success: function () {
        CartService1.fetchPendingCartItems();
      },
      error: function (err) {
        console.error("Failed to remove item", err);
        Swal.fire("Error", "Could not remove item from cart.", "error");
      }
    });
  },

  completeOrder: function () {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (!user) {
      Swal.fire("Error", "Please log in to complete your order.", "error");
      return;
    }

    if (!CartService1.pendingOrderId) {
      Swal.fire("Error", "No pending order found to complete.", "error");
      return;
    }

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}orders/complete/${CartService1.pendingOrderId}/${user.id}`,
      type: "PUT",
      headers: {
        "Authentication": token
      },
      success: function () {
        Swal.fire("Success", "Payment completed and order placed!", "success");
        CartService1.fetchPendingCartItems();
        $("#checkout-form")[0].reset();
      },
      error: function (err) {
        console.error("Failed to complete order", err);
        Swal.fire("Error", "Failed to complete order. Please try again.", "error");
      }
    });
  }
};



$(document).ready(function () {
  CartService1.fetchPendingCartItems();
});
