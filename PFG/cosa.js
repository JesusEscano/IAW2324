// Define las variables globales del juego
let currentPlayer = 1; // Jugador actual
let playerHands = {}; // Mano de cada jugador
let gameField = []; // Cartas en el campo de juego

// Definir el objeto de imágenes de cartas
const cardImages = {
    '1': 'imagenes/1.png',
    '2': 'imagenes/2.png',
    '3': 'imagenes/3.png',
    '4': 'imagenes/4.png',
    '5': 'imagenes/5.png',
    '6': 'imagenes/6.png',
    '7': 'imagenes/7.png',
    '8': 'imagenes/8.png',
    '9': 'imagenes/9.png',
    '10': 'imagenes/10.png',
    '11': 'imagenes/11.png',
    '12': 'imagenes/12.png',
    '1ba': 'imagenes/1ba.png',
    '2ba': 'imagenes/2ba.png',
    '3ba': 'imagenes/3ba.png',
    '4ba': 'imagenes/4ba.png',
    '5ba': 'imagenes/5ba.png',
    '6ba': 'imagenes/6ba.png',
    '7ba': 'imagenes/7ba.png',
    '8ba': 'imagenes/8ba.png',
    '9ba': 'imagenes/9ba.png',
    '10ba': 'imagenes/10ba.png',
    '11ba': 'imagenes/11ba.png',
    '12ba': 'imagenes/12ba.png',
    '1copa': 'imagenes/1copa.png',
    '2copa': 'imagenes/2copa.png',
    '3copa': 'imagenes/3copa.png',
    '4copa': 'imagenes/4copa.png',
    '5copa': 'imagenes/5copa.png',
    '6copa': 'imagenes/6copa.png',
    '7copa': 'imagenes/7copa.png',
    '8copa': 'imagenes/8copa.png',
    '9copa': 'imagenes/9copa.png',
    '10copa': 'imagenes/10copa.png',
    '11copa': 'imagenes/11copa.png',
    '12copa': 'imagenes/12copa.png',
    '1espa': 'imagenes/1espa.png',
    '2espa': 'imagenes/2espa.png',
    '3espa': 'imagenes/3espa.png',
    '4espa': 'imagenes/4espa.png',
    '5espa': 'imagenes/5espa.png',
    '6espa': 'imagenes/6espa.png',
    '7espa': 'imagenes/7espa.png',
    '8espa': 'imagenes/8espa.png',
    '9espa': 'imagenes/9espa.png',
    '10espa': 'imagenes/10espa.png',
    '11espa': 'imagenes/11espa.png',
    '12espa': 'imagenes/12espa.png',
};

// Función para iniciar el juego
function startGame() {
    // Repartir cartas a los jugadores
    dealCards();
    // Mostrar las cartas del jugador actual
    displayPlayerCards(currentPlayer);
}

// Función para repartir cartas a los jugadores
function dealCards() {
    const totalCards = 48; // Total de cartas en el mazo
    const cardsPerPlayer = 12; // Cartas por jugador

    const deck = []; // Baraja de cartas
    for (let i = 1; i <= 12; i++) {
        deck.push(`${i}`, `${i}ba`, `${i}copa`, `${i}espa`); // Agregar todas las cartas al mazo
    }

    // Repartir las cartas a los jugadores
    for (let i = 1; i <= 4; i++) {
        playerHands[i] = []; // Inicializar la mano del jugador
        for (let j = 0; j < cardsPerPlayer; j++) {
            const randomIndex = Math.floor(Math.random() * deck.length); // Obtener un índice aleatorio
            const randomCard = deck.splice(randomIndex, 1)[0]; // Quitar la carta aleatoria del mazo
            playerHands[i].push(randomCard); // Agregar la carta a la mano del jugador
        }
    }
}

// Función para mostrar las cartas del jugador actual
function displayPlayerCards(player) {
    // Implementa la lógica para mostrar las cartas del jugador actual en el DOM
    const playerContainer = document.querySelector(`#game-container`);
    playerContainer.innerHTML = ''; // Limpiar el contenedor antes de agregar las cartas

    playerHands[player].forEach(card => {
        const cardElement = document.createElement('img');
        cardElement.src = cardImages[card];
        cardElement.classList.add('card');
        playerContainer.appendChild(cardElement);
    });
}

// Iniciar el juego al cargar la página
window.onload = function() {
    startGame();
};