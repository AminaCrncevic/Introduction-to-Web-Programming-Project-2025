let Constants = {
  // PROJECT_BASE_URL: "http://localhost:8080/Introduction-to-Web-Programming-Project-2025/backend/",
   PROJECT_BASE_URL: (() => {
    const hostname = window.location.hostname;
    if (hostname === 'localhost' || hostname === '127.0.0.1') {
      return "http://localhost:8080/Introduction-to-Web-Programming-Project-2025/backend/";
    } else {
      return "https://sea-lion-app-wwmg2.ondigitalocean.app/";
    }
  })(),
   USER_ROLE: "user",
   ADMIN_ROLE: "admin"
}
