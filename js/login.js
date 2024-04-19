//JS file for logging in.
let checkboxHideRevealPassword = document.getElementById("show-hide-password"),
    password = document.getElementById("password");

checkboxHideRevealPassword.addEventListener("change", () => {
   if(checkboxHideRevealPassword.checked) {
       password.type = "text";
   } else {
       password.type = "password";
   }
});