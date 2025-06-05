const CartService = {
  addToCart: function (productId, quantity = 1) {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (!user) {
      alert("Please log in to add items to cart.");
      return;
    }
const userId = user.id;

    $.ajax({
      url: Constants.PROJECT_BASE_URL + "orders/add-item",
      method: "POST",
      contentType: "application/json",
      headers: {
        "Authentication": token
      },
      data: JSON.stringify({
        user_id: userId,
        product_id: productId,
        quantity: quantity
      }),
      success: function (res) {
   
        Swal.fire("Success", "Product added to cart successfully!", "success");
        CartService1.fetchPendingCartItems();
      },
      error: function (err) {
        alert("Failed to add item to cart.");
        console.error(err);
      }
    });
  }
};


$(document).on("click", ".cart-btn", function (e) {
  e.preventDefault();
  const productId = $(this).data("product-id");
  CartService.addToCart(productId);
});

