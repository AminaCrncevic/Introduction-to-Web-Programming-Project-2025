const ProductService = {
  fetchProducts: function () {
    const userToken = localStorage.getItem("user_token");
    if (!userToken) {
      console.error("Authentication token missing.");
      return;
    }

    $.ajax({
      url: Constants.PROJECT_BASE_URL + "product",
      type: "GET",
      headers: {
        "Authentication": userToken
      },
      success: function (products) {
        ProductService.renderProducts(products);
      },
      error: function (err) {
        console.error("Failed to load products", err);
      }
    });
  },



  renderProducts: function (products) {
    const container = $("#productContainer");
    container.empty(); 

    products.forEach(product => {
      const box = $(`
        <div class="box" data-product-id="${product.id}">
            <div class="image">
              <img src="${product.ProductImage}" alt="${product.ProductName}">
              <div class="icons">
                  <button class="cart-btn" data-product-id="${product.id}" data-product-name="${product.ProductName}" data-product-price="${product.ProductPrice}">
                Add to cart
              </button>
                <button class="wishlist-btn" data-product-id="${product.id}">
                  <i class="fa-regular fa-heart"></i>
                </button>
              </div>
            </div>
          </a>
          <div class="content">
            <h3>${product.ProductName}</h3>
            <div class="price">${product.ProductPrice}$</div>
          </div>
        </div>
      `);
box.on("click", function (e) {
  if ($(e.target).closest("button").length > 0) return;
  const productId = $(this).data("product-id");
 ProductService.showProductDetail(productId);
 window.location.hash = `#productDetailPage`;
});
  container.append(box);
    });
  },



showProductDetail: function (productId) {
    const token = localStorage.getItem("user_token");
    
    $.ajax({
      url: Constants.PROJECT_BASE_URL + `product/${productId}`,
      method: "GET",
      headers: {
        "Authentication": token
      },
      success: function (product) {
        const html = `
          <div class="containerpd" id="${product.id}">                    
            <img class="product-img" src="${product.ProductImage}" alt="${product.ProductName}">
            <h1 class="product-title" id="product-name">${product.ProductName}</h1>
            <p class="product-price" id="product-price">${parseFloat(product.ProductPrice).toFixed(2)}$</p>
            <form id="add-to-cart-form" data-product-id="${product.id}">
              <button type="submit" class="btnpd">Add to Cart</button>
            </form>
            <p class="product-description">${product.ProductDescription}</p>
          </div>
        `;

        $("#main-content").hide(); 
        $("#product-detail-container").html(html).show();
        $("#add-to-cart-form").on("submit", function (e) {
    e.preventDefault();
    const productId = $(this).data("product-id");
    CartService.addToCart(productId, 1);
  });
},
      error: function (err) {
        Swal.fire("Error", "Failed to load product details.", "error");
        console.error("Product detail error:", err);
      }
    });
  }

};


$(document).ready(function () {
  ProductService.fetchProducts();
});
