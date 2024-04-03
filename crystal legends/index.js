document.addEventListener("DOMContentLoaded", function() {
    var backgroundMusic = document.getElementById("background-music");
  
    // Reproducir la música de fondo
    function playBackgroundMusic() {
      backgroundMusic.play();
    }
  
    // Pausar la música de fondo
    function pauseBackgroundMusic() {
      backgroundMusic.pause();
    }
  
    // Rebobinar la música de fondo al principio
    function rewindBackgroundMusic() {
      backgroundMusic.currentTime = 0;
    }
  
    // Event listener para iniciar la reproducción cuando se hace clic en algún lugar de la página
    document.body.addEventListener("click", function() {
      playBackgroundMusic();
    });
  
    // Iniciar la reproducción de la música de fondo cuando se carga la página
    playBackgroundMusic();
  });
document.addEventListener("DOMContentLoaded", function() {
    var hoverSound = document.getElementById("hover-sound");

    // Reproduce el sonido al hacer hover sobre las imágenes
    var cardImages = document.querySelectorAll(".card-image-ase, .card-image-arq, .card-image-drag, .card-image-berser, .card-image-gue, .card-image-mb, .card-image-mn, .card-image-ilu");

    cardImages.forEach(function(image) {
        image.addEventListener("mouseenter", function() {
            hoverSound.play();
        });
    });
});