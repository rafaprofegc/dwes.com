// Uso de las palabras clave await y async
function registra_eventos() {
    const botonSumar = document.getElementById("sumar");
    botonSumar.addEventListener("click", sumarNumeros);
}

async function sumarNumeros() {
    let n1 = document.getElementById("n1");
    let n2 = document.getElementById("n2");

    if( n1.value == "" || n2.value == "" ) {
        alert("Los números no pueden estar vacíos");
        return;
    }

    let num1 = parseFloat(n1.value);
    let num2 = parseFloat(n2.value);

    if( !num1 || !num2 ) {
        alert("Solo podemos sumar números");
        return;
    }

    // En este punto tenemos dos números para sumar
    const cuerpo = {
        jsonrpc : "2.0",
        method: "Matematicas.suma",
        params: [num1, num2],
        id: 1234
    };

    const peticion = {
        method: "POST",
        headers: { Accept: "application/json"},
        body: JSON.stringify(cuerpo)
    };

    try {
        const respuesta = await fetch("http://dwes.com/ra7/rpc/index.php", peticion);

        if( !respuesta.ok ) throw new Error("Error en la respuesta: " + respuesta.status);

        let resultado_json = await respuesta.json();

        let texto_resultado = document.getElementById("resultado");
        texto_resultado.value = resultado_json.result;
    }
    catch( error ) {
        console.log(error.message);
    }
    
}