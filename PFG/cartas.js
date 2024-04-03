const cardObjectDefinitions = [
    { id: 1, imagePath: 'imagenes/1.png' },
    { id: 2, imagePath: 'imagenes/2.png' },
    { id: 3, imagePath: 'imagenes/3.png' },
    { id: 4, imagePath: 'imagenes/4.png' },
    { id: 5, imagePath: 'imagenes/5.png' },
    { id: 6, imagePath: 'imagenes/6.png' },
    { id: 7, imagePath: 'imagenes/7.png' },
    { id: 8, imagePath: 'imagenes/8.png' },
    { id: 9, imagePath: 'imagenes/9.png' },
    { id: 10, imagePath: 'imagenes/10.png' },
    { id: 11, imagePath: 'imagenes/11.png' },
    { id: 12, imagePath: 'imagenes/12.png' }
];

const cardBackImgPath = 'imagenes/reverso.png'; 

const cardContainerElem = document.querySelector('.card-container'); 

function creaCarta(cardItem) {
    // Los elementos que componen una carta
    const cardElem = createElement('div');
    const cardInnerElem = createElement('div');
    const cardFrontElem = createElement('div');
    const cardBackElem = createElement('div');

    // Los elementos que componen el contenido de la carta (imágenes)
    const cardFrontImg = createElement('img');
    const cardBackImg = createElement('img');

    // Añadiendo clases e id a cada elemento de la carta
    addClassToElement(cardElem, 'card');
    addIdToElement(cardElem, 'card' + cardItem.id); 

    addClassToElement(cardInnerElem, 'card-inner');
    addClassToElement(cardFrontElem, 'card-front');
    addClassToElement(cardBackElem, 'card-back');

    // Estableciendo la imagen de reverso y frente de la carta
    addSrcToImageElement(cardBackImg, cardBackImgPath);
    addClassToElement(cardBackImg, 'card-img');

    addSrcToImageElement(cardFrontImg, cardItem.imagePath);
    addClassToElement(cardFrontImg, 'card-img');

    // Agregando las imágenes al frente y reverso de la carta
    addChildElement(cardFrontElem, cardFrontImg);
    addChildElement(cardBackElem, cardBackImg);

    // Agregando el frente y el reverso a la parte interna de la carta
    addChildElement(cardInnerElem, cardFrontElem);
    addChildElement(cardInnerElem, cardBackElem);

    // Agregando la parte interna de la carta al contenedor de la carta
    addChildElement(cardElem, cardInnerElem);

    // Agregando la carta al contenedor principal
    addChildElement(cardContainerElem, cardElem);
}

function createElement(elemType) {
    return document.createElement(elemType);
}

function addClassToElement(elem, className) {
    elem.classList.add(className);
}

function addIdToElement(elem, id) {
    elem.id = id;
}

function addSrcToImageElement(imgElem, src) {
    imgElem.src = src;
}

function addChildElement(parentElem, childElem) {
    parentElem.appendChild(childElem);
}

// Crear las cartas
cardObjectDefinitions.forEach(creaCarta);