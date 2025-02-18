function registra_eventos() {
    const botonSumar = document.getElementById("sumar");
    botonSumar.addEventListener("click", sumarNumeros);
}

function sumarNumeros() {
    const n1 = document.getElementById("n1");
    const n2 = document.getElementById("n2");

    if( n1.value == "" || n2.value == "" ) {
        alert("Los valores de los números no pueden estar vacíos");
        return;
    }

    const num1 = parseFloat(n1.value);
    const num2 = parseFloat(n2.value);
    if( !num1 || !num2 ) {
        alert("Los valores tienen que ser numéricos");
        return;
    }

    // En este punto los números son correctos.

    // 1º Creamos el objeto XMLHttpRequest (AJAX Asynchronous JavaScript And XML)
    const peticionHttp = new XMLHttpRequest();

    // 2º Hacer la petición 
    peticionHttp.open("POST", "http://dwes.com/ra7/rpc/index.php");

    // 3º Indicamos una cabecera Accept
    peticionHttp.setRequestHeader("Accept", "application/json");

    // 4º Procesamiento de la respuesta
    peticionHttp.onreadystatechange = () => {
        if( peticionHttp.readyState == XMLHttpRequest.DONE ) {
            if( peticionHttp.status == 200 ) {
                let respuesta = JSON.parse(peticionHttp.responseText);
                if( respuesta.error ) {
                    alert(respuesta.error.code + " " + respuesta.error.message);
                    return;
                }
                else {
                    let resultado = document.getElementById('resultado');
                    resultado.value = respuesta.result;
                }

            }
        }
    }
    // 5º Enviar la petición
    const cuerpo = {
        jsonrpc: "2.0",
        method: "Matematicas.suma",
        params: [num1, num2],
        id: 1
    }
    peticionHttp.send(JSON.stringify(cuerpo));
}

