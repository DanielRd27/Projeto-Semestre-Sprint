// Register
let buttonRegisterToLogin = document.getElementById("btn-redirection-login");

// Login 
let buttonLoginToRegister = document.getElementById("btn-redirection-register");

// Main card
let mainCard = document.getElementById("mainCard");

let box = document.querySelector(".hover");
let boxContainer = document.querySelector(".itemSlider");

buttonLoginToRegister.onclick = () => {
    mainCard.classList.add("registerActive")
    mainCard.classList.remove("loginActive")
};

buttonRegisterToLogin.onclick = () => {
    mainCard.classList.remove("registerActive")
    mainCard.classList.add("loginActive")
};

boxContainer.addEventListener('mouseover', () => {
  box.style.transition = 'transform 1s';
  box.style.opacity = 'opacity 1';
});

boxContainer.addEventListener('mouseout', () => {
  box.style.transition = 'transform 0s';
  box.style.opacity = 'opacity 0';
});