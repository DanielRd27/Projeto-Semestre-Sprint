// Register

let cardRegisterAside = document.getElementById("card-registerAside");

let buttonRegisterToLogin = document.getElementById("btn-redirection-login");

let cardRegister = document.getElementById("card-register");

// Login 

let cardLoginAside = document.getElementById("card-loginAside");

let buttonLoginToRegister = document.getElementById("btn-redirection-register");

let cardLogin = document.getElementById("card-login");

// Main login

let mainLogin = document.getElementById("mainLogin")


buttonLoginToRegister.onclick = () => {
    cardLoginAside.classList.add("d-none")
    cardLogin.classList.add("d-none")

    cardRegisterAside.classList.remove("d-none")
    cardRegister.classList.remove("d-none")
};

buttonRegisterToLogin.onclick = () => {
    cardRegisterAside.classList.add("d-none")
    cardRegister.classList.add("d-none")

    cardLoginAside.classList.remove("d-none")
    cardLogin.classList.remove("d-none")
};