
var resultado = 0
    function sumar(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        return x+y;
    }
    function restar(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        return x-y;
    }
    function division(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        if (y!=0){
            return x/y;
        }
        else {return 'No se puede dividir por cero'}
    }
    function multiplicar(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        return x*y;
    }
    function potencia(){
        let x = parseFloat(document.getElementById('numero1').value);
        let y = parseFloat(document.getElementById('numero2').value);
        return Math.pow(x,y);
    }