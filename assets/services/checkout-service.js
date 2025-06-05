const CheckoutService = {
  init: function () {
    $(document).ready(function () {
      CartService1.fetchPendingCartItems();

      $("#checkout-form").validate({
        rules: {
          name: "required",
          email: {
            required: true,
            email: true
          },
          address: "required",
          city: "required",
          zipcode: {
            required: true,
            minlength: 4
          },
          country: "required",
          card_number: {
            required: true,
            digits: true,
            minlength: 12,
            maxlength: 19
          },
          cvv: {
            required: true,
            digits: true,
            minlength: 3,
            maxlength: 4
          },
          name_on_card: "required"
        },
        messages: {
          name: "Enter your full name",
          email: {
            required: "Enter your email",
            email: "Invalid email format"
          },
          address: "Enter your address",
          city: "Enter your city",
          zipcode: {
            required: "Enter ZIP code",
            minlength: "ZIP must be at least 4 digits"
          },
          country: "Enter your country",
          card_number: {
            required: "Enter card number",
            digits: "Only digits allowed",
            minlength: "Card number too short",
            maxlength: "Card number too long"
          },
          cvv: {
            required: "Enter CVV",
            digits: "Only digits allowed",
            minlength: "Too short",
            maxlength: "Too long"
          },
          name_on_card: "Enter name on card"
        },
        submitHandler: function (form) {
          $.blockUI({ message: "<h3>Processing Payment...</h3>" });

          setTimeout(() => {
            CartService1.completeOrder();
            $.unblockUI(); 
          }, 500);
        }
      });
    });
  }
};
