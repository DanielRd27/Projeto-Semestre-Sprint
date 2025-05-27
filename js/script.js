// Register
let buttonRegisterToLogin = document.getElementById("btn-redirection-login");

// Login 
let buttonLoginToRegister = document.getElementById("btn-redirection-register");

// Main card
let mainCard = document.getElementById("mainCard");

buttonLoginToRegister.onclick = () => {
    mainCard.classList.add("registerActive")
    mainCard.classList.remove("loginActive")
};

buttonRegisterToLogin.onclick = () => {
    mainCard.classList.remove("registerActive")
    mainCard.classList.add("loginActive")
};