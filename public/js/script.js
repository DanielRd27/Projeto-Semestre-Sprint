// Sliders

    document.querySelectorAll('.itemSlider').forEach(slider => {
        let hoverBox = slider.querySelector('.hover');
        
        slider.addEventListener('mouseenter', () => {
            console.log("hover in");
            hoverBox.style.transition = 'opacity 1s ease';
        });
        
        slider.addEventListener('mouseleave', () => {
            hoverBox.style.transition = 'opacity 0s ease';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona os elementos
        const sliderWrapper = document.querySelector('.slider-wrapper');
        const sliderContainer = document.querySelector('.sliderMovies-container');
        const sliderTrack = document.querySelector('.sliderMovies');
        const leftArrow = document.querySelector('.left-arrow');
        const rightArrow = document.querySelector('.right-arrow');
        const sliderItems = document.querySelectorAll('.itemSlider');
        
        // Configurações
        const itemGap = 16; // Espaço entre itens (deve corresponder ao gap do CSS)
        let currentPosition = 0;
        let itemWidth = sliderItems[0].offsetWidth + itemGap;
        let maxScroll = sliderTrack.scrollWidth - sliderContainer.offsetWidth;
        
        // Atualiza as medidas quando a janela é redimensionada
        window.addEventListener('resize', function() {
            itemWidth = sliderItems[0].offsetWidth + itemGap;
            maxScroll = sliderTrack.scrollWidth - sliderContainer.offsetWidth;
            updateArrows();
        });
        
        // Função para mover o slider
        function moveSlider(direction) {
            const containerWidth = sliderContainer.offsetWidth;
            const visibleItems = Math.floor(containerWidth / itemWidth);
            
            if (direction === 'left') {
                currentPosition += itemWidth * visibleItems;
                if (currentPosition > 0) currentPosition = 0;
            } else {
                currentPosition -= itemWidth * visibleItems;
                if (currentPosition < -maxScroll) currentPosition = -maxScroll;
            }
            
            sliderTrack.style.transform = `translateX(${currentPosition}px)`;
            updateArrows();
        }
        
        // Atualiza o estado das setas
        function updateArrows() {
            leftArrow.disabled = currentPosition >= 0;
            rightArrow.disabled = currentPosition <= -maxScroll;
            
            // Estilização visual quando desativadas
            leftArrow.style.opacity = currentPosition >= 0 ? '0.5' : '1';
            rightArrow.style.opacity = currentPosition <= -maxScroll ? '0.5' : '1';
        }
        
        // Eventos de clique nas setas
        leftArrow.addEventListener('click', () => moveSlider('left'));
        rightArrow.addEventListener('click', () => moveSlider('right'));
        
        // Inicializa verificando o estado das setas
        updateArrows();
        
        // Opcional: Navegação por teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                moveSlider('left');
            } else if (e.key === 'ArrowRight') {
                moveSlider('right');
            }
        });
    });

// Sistema de pesquisa de Itens

    const searchInput = document.getElementById('searchInput');
    const resultsContainer = document.getElementById('autocompleteResults');

    // Debounce para evitar muitas requisições
    function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
    }

    // Busca no backend
    async function fetchSuggestions(query) {
    if (!query) {
        resultsContainer.innerHTML = '';
        return;
    }

    try {
        const response = await fetch(`search.php?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        showResults(data);
    } catch (error) {
        console.error('Erro ao buscar:', error);
    }
    }

    // Mostra resultados
    function showResults(items) {
    resultsContainer.innerHTML = '';
    
    items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'autocomplete-item';
        div.textContent = item.nome; // Altere para o campo do seu DB
        div.addEventListener('click', () => {
        searchInput.value = item.nome;
        resultsContainer.innerHTML = '';
        });
        resultsContainer.appendChild(div);
    });
    }

    // Evento com debounce
    searchInput.addEventListener('input', debounce(() => {
    fetchSuggestions(searchInput.value);
    }));

    // Fecha resultados ao clicar fora
    document.addEventListener('click', (e) => {
    if (e.target !== searchInput) {
        resultsContainer.innerHTML = '';
    }
    });

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

