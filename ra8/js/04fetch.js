function registra_eventos() {
    const botonSumar = document.getElementById("sumar");
    botonSumar.addEventListener("click", sumarNumeros);
}

function sumarNumeros() {
    let n1 = document.getElementById("n1");
    let n2 = document.getElementById("n2");

    if( n1.value == "" || n2.value == "" ) {
        alert("Ningún número puede estar en blanco");
        return;
    }

    num1 = parseFloat(n1.value);
    num2 = parseFloat(n2.value);
    if( !num1 || !num2 ) {
        alert("Solo se pueden sumar números");
        return;
    }

    // Los números son correctos y podemos hacer la petición
    const cuerpo = {
        jsonrpc: "2.0",
        method: "Matematicas.suma",
        params: [num1, num2],
        id: 1234
    }
    
    const peticion = {
        method: "POST",
        headers: { Accept: "application/json"},
        body: JSON.stringify(cuerpo)
    };

    /*
    fetch("http://dwes.com/ra7/rpc/index.php", peticion)
    .then( respuesta => {
        if( !respuesta.ok ) {
            throw new Error("Error en la respuesta: " + respuesta.status)
        }

        return respuesta.json();
    }).then( respuesta_json => {
        let resultado = respuesta_json.result;

        const texto_resultado = document.getElementById("resultado");
        texto_resultado.value = resultado;

    }).catch( error => {
        console.log(error.message);
    });
    */
    const promesa1 = fetch("http://dwes.com/ra7/rpc/index.php", peticion);

    const promesa2 = promesa1.then( respuesta => {
        if( !respuesta.ok ) {
            throw new Error("Error en la respuesta: " + respuesta.status)
        }

        return respuesta.json();
    })
    
    const promesa3 = promesa2.then( respuesta_json => {
        let resultado = respuesta_json.result;

        return resultado;

    });

    const promesa4 = promesa3.then( suma => {

        const texto_resultado = document.getElementById("resultado");
        texto_resultado.value = suma;
    });
    
    promesa4.catch( error => {
        console.log(error.message);
    });
}