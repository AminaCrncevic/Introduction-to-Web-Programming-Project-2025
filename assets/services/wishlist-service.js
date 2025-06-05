const WishlistService = {
  init: function () {
    this.fetchWishlistItems();
  },

  addToWishlist: function (productId) {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (!user) {
      alert("Please log in to add items to your wishlist.");
      return;
    }

    const userId = user.id;

    $.ajax({
      url: Constants.PROJECT_BASE_URL + "wishlistitems",
      method: "POST",
      contentType: "application/json",
      headers: {
        "Authentication": token
      },
      data: JSON.stringify({
        user_id: userId,
        product_id: productId
      }),
      success: function () {
        Swal.fire("Success", "Product added to wishlist!", "success");
        WishlistService.fetchWishlistItems();
      },
      error: function (err) {
        const errorMsg = err.responseJSON?.error || "Failed to add to wishlist.";
        Swal.fire("Error", errorMsg, "error");
        console.error("Add to wishlist error:", err);
      }
    });
  },

  fetchWishlistItems: function () {
    const token = localStorage.getItem("user_token");
    const user = Utils.parseJwt(token)?.user;

    if (!user) {
      alert("Please log in to view your wishlist.");
      return;
    }

    const userId = user.id;

    $.ajax({
      url: Constants.PROJECT_BASE_URL + "wishlistitems/" + userId,
      method: "GET",
      headers: {
        "Authentication": token
      },
      success: function (wishlistItems) {
        WishlistService.renderWishlist(wishlistItems);
      },
      error: function (err) {
        Swal.fire("Error", "Failed to fetch wishlist items", "error");
        console.error("Fetch wishlist error:", err);
      }
    });
  },

  removeFromWishlist: function (wishlistItemId) {
    const token = localStorage.getItem("user_token");

    $.ajax({
      url: Constants.PROJECT_BASE_URL + "wishlistitems/" + wishlistItemId,
      method: "DELETE",
      headers: {
        "Authentication": token
      },
      success: function () {
        Swal.fire("Removed", "Item removed from wishlist.", "success");
        WishlistService.fetchWishlistItems();
      },
      error: function (err) {
        const errorMsg = err.responseJSON?.error || "Failed to remove item.";
        Swal.fire("Error", errorMsg, "error");
        console.error("Remove wishlist item error:", err);
      }
    });
  },

  renderWishlist: function (items) {
    const container = $("#wishlist-container");
    container.empty();

    if (!items || items.length === 0) {
      container.append("<p>Your wishlist is empty.</p>");
      return;
    }

    items.forEach(item => {
      const card = $(`
        <div class="wishlist-item">
          <img src="${item.ProductImage}" alt="${item.ProductName}">
          <h3>${item.ProductName}</h3>
          <p>${parseFloat(item.ProductPrice).toFixed(2)} $</p>
          <button class="remove-btn" data-wishlist-id="${item.id}">Remove</button>
        </div>
      `);
      container.append(card);
    });
  }
};


$(document).on("click", ".wishlist-btn", function (e) {
  e.preventDefault();
  const productId = $(this).data("product-id");
  WishlistService.addToWishlist(productId);
});


$(document).on("click", ".remove-btn", function () {
  const wishlistItemId = $(this).data("wishlist-id");
  WishlistService.removeFromWishlist(wishlistItemId);
});
