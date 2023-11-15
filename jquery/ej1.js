/* Rellena este fichero */
$(document).ready(function () {
    $("#btn-ocultar").click(function(){
        $('h1').hide(3000);
        $('p').hide(3000);
        });
    $("#btn-mostrar").click(function(){
        $('h1').show(3000);
        $('p').show(3000);
        });
});