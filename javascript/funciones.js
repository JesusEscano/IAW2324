
    var resultado = 0
    function sumar(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        resultado = x+y;
    }
    function restar(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        resultado = x-y;
    }
    function division(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        if (y!=0){
            resultado = x/y;
        }
        else {resultado = 'No se puede dividir por cero'}
    }
    function multiplicar(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        resultado = x*y;
    }
    function potencia(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        resultado = Math.pow(x,y);
    }