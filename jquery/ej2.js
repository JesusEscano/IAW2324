/* Rellena este fichero */
$(document).ready(function () {
    $("#btn-ocultar").click(function(){
        $("#encabezado").hide(3000);
        $('.pares').hide(3000);
        });
    $("#btn-mostrar").click(function(){
        $('#encabezado').show(3000);
        $('.pares').show(3000);
        });
});