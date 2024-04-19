//JS when creating a new account.
let loggingText = document.getElementById("logging-text"),
    username = document.getElementById("username"),
    password = document.getElementById("password"),
    passwordCheck = document.getElementById("password-check"),
    checkboxShowPassword = document.getElementById("show-hide-password"),
    registerForm = document.getElementById("form-register");

//Event-listener which reveals the password and hides it.
checkboxShowPassword.addEventListener("change", () => {
    console.log("test")
   if(checkboxShowPassword.checked) {
       password.type = "text";
       passwordCheck.type = "text";
   } else {
       password.type = "password";
       passwordCheck.type = "password";
   }
});

//Event-listener for the form to first check if the passwords match and weather a username has been provided.
registerForm.addEventListener("submit", (event) => {
   event.preventDefault();

    console.log("test test");
    if(password.value !== passwordCheck.value) {
       loggingText.innerHTML = "The passwords don't match!"
       loggingText.style.color = "red";
       return;
   }

   registerForm.submit();
});


