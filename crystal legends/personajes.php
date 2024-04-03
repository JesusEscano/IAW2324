<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="font/webfont.css">
<title>Selección de Personajes</title>
<style>
    body, .Reactor7 {
        font-family: 'Reactor7', Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    h1 {
        text-align: center;
    }
    .container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding: 20px;
    }
    .character {
        text-align: center;
        cursor: pointer;
    }
    .character img {
        max-width: 100%;
        max-height: 100%;
        border: 2px solid transparent;
        transition: border-color 0.3s;
    }
    .character p {
        margin-top: 10px;
        display: none;
    }
    .character:hover img {
        border-color: blue;
    }
    .character-details {
        width: 40%;
        padding: 20px;
        float: right;
        margin-right: 10%; /* Espacio entre el área de los personajes y los detalles */
        border: 2px solid #ccc;
        border-radius: 5px;
    }
</style>
</head>
<body>
<h1>ELIGE TU PERSONAJE</h1>
<div class="container">
    <div class="character" onclick="showCharacter('Asesino')">
        <img src="imagenes/asesino.png" alt="Asesino">
        <p>Asesino</p>
    </div>
    <div class="character" onclick="showCharacter('Arquero')">
        <img src="imagenes/arquero.png" alt="Arquero">
        <p>Arquero</p>
    </div>
    <div class="character" onclick="showCharacter('Berserker')">
        <img src="imagenes/berserker.png" alt="Berserker">
        <p>Berserker</p>
    </div>
    <div class="character" onclick="showCharacter('Ilusionista')">
        <img src="imagenes/ilusionista.png" alt="Ilusionista">
        <p>Ilusionista</p>
    </div>
    <div class="character" onclick="showCharacter('Invocadora')">
        <img src="imagenes/invocadora.png" alt="Invocadora">
        <p>Invocadora</p>
    </div>
    <div class="character" onclick="showCharacter('Guerrero')">
        <img src="imagenes/guerrero.png" alt="Guerrero">
        <p>Guerrero</p>
    </div>
    <div class="character" onclick="showCharacter('Mago Negro')">
        <img src="imagenes/mnegro.png" alt="Mago Negro">
        <p>Mago Negro</p>
    </div>
    <div class="character" onclick="showCharacter('Mago Blanco')">
        <img src="imagenes/mblanco.png" alt="Mago Blanco">
        <p>Mago Blanco</p>
    </div>
</div>
<div class="character-details" id="character-details">
    <h2 id="selected-character-name"></h2>
    <img src="" alt="" id="selected-character-image">
    <p id="selected-character-description"></p>
</div>

<script>
    function showCharacter(characterName) {
        // Actualizar el nombre del personaje seleccionado
        var selectedCharacterName = document.getElementById('selected-character-name');
        selectedCharacterName.textContent = characterName;

        // Actualizar la imagen del personaje seleccionado
        var selectedCharacterImage = document.getElementById('selected-character-image');
        selectedCharacterImage.src = 'imagenes/' + characterName.toLowerCase() + 'CL.png';
        selectedCharacterImage.alt = characterName;

        // Aquí puedes actualizar la descripción del personaje seleccionado según sea necesario
        var selectedCharacterDescription = document.getElementById('selected-character-description');
        // Por ahora, dejamos la descripción en blanco
        selectedCharacterDescription.textContent = 'Descripción del personaje: ';

        // Mostrar el área de detalles del personaje
        var characterDetails = document.getElementById('character-details');
        characterDetails.style.display = 'block';
    }
</script>

</body>
</html>