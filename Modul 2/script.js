function togglePasswordVisibility() {
  var passwordInput = document.getElementById("password");
  var checkbox = document.getElementById("showPassword");

  passwordInput.type = checkbox.checked ? "text" : "password";
}

document
  .getElementById("showPassword")
  .addEventListener("change", togglePasswordVisibility);
