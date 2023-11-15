/*Conjunto de ejercicios de jQuery (escribir jqdoc para encontrar la forma de inciar to rápido) */
$(document).ready(function () {
    let parrafo;
    $("#esconder").click(function () { 
        parrafo = prompt("Dime qué quieres esconder").toLocaleLowerCase
        if (parrafo == 'todos'){$("p").hide()}
        else {$('#'+parrafo).hide();}
    });
});

