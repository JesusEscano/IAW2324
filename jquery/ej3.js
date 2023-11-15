/* Rellena este fichero */
$(document).ready(function () {
$("#btn-aumentar").click(function(){
    let actual = $('.pares').css('font-size');
    $(".pares").css({
    "font-size": parseInt(actual)+10+"px",
    "color": "red"}
    )});
$("#btn-disminuir").click(function(){
    let actual = $('.pares').css('font-size');
    $(".pares").css({
    "font-size": parseInt(actual)-10+"px",
    "color": "black"}
    )
        });
    });