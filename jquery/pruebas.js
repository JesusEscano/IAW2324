/*Conjunto de ejercicios de jQuery (escribir jqdoc para encontrar la forma de inciar to rápido) */
$(document).ready(function () {

    $("#esconder").click(function () { 
        $("#tercero").append("<p>"+contador+"</p>");
    });
    $("#mostrar").click(function () { 
        $("p").addClass("roja");
    });
    $("#alternar").click(function () { 
      alert($("#campo").val());
    });
    $(".roja").mousseenter(function (){
        alert($("#primero").html());
    })
});

