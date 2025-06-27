const GenerateMessageService = {
  generateMessage: function (recipientName, occasion, tone, callback) {
    const token = localStorage.getItem("user_token");

    $.ajax({
      url: `${Constants.PROJECT_BASE_URL}ai/message`,
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authentication": token
      },
      data: JSON.stringify({
        recipient_name: recipientName,
        occasion: occasion,
        tone: tone
      }),
      success: function (response) {
        if (callback) callback(null, response.message);
      },
      error: function (xhr) {
        console.error("AI message generation failed:", xhr);
        if (callback) callback("Failed to generate message.");
      }
    });
  }
};

