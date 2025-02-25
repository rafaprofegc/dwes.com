function registra_eventos() {
    const botonEnvio = document.getElementById("enviar");
    botonEnvio.addEventListener("click", presentaArticulo);
}

function presentaArticulo() {
    let cuadro_referencia = document.getElementById("referencia");
    if( cuadro_referencia.value == "" ) {
        alert("Hay que indicar una referencia de artículo");
        return;
    }

    const peticion = {
        jsonrpc: "2.0",
        method: "RpcOrmArticulo.obtenerArticulo",
        params: [cuadro_referencia.value],
        id: 1234
    };

    // Crear el objeto XMLHttpRequest
    const peticionHttp = new XMLHttpRequest();

    // Indico la URL y el método de la petición
    peticionHttp.open("POST", "http://dwes.com/ra7/rpc/index.php");

    // Añado la cabecera Accept
    peticionHttp.setRequestHeader("Accept", "application/json");

    peticionHttp.onreadystatechange = () => {
        if( peticionHttp.readyState == XMLHttpRequest.DONE ) {
            if( peticionHttp.status == 200 ) {
                let respuesta = JSON.parse(peticionHttp.responseText);
                if( respuesta.error ) {
                    console.log("Error: " + respuesta.error.code + " " + respuesta.error.message);
                }
                else {
                    presentarDatos(respuesta.result);
                }
            }
        }
    }

    peticionHttp.send(JSON.stringify(peticion));
}

function presentarDatos(articulo) {
    let capa = document.getElementById("resultado");
    capa.innerHTML = "<h3>Resultado de la búsqueda</h3><p>";
    capa.innerHTML += `Referencia: ${articulo.referencia}<br>`;
    capa.innerHTML += `Descripción: ${articulo.descripcion}<br>`;
    capa.innerHTML += `PVP: ${articulo.pvp}€<br>`;
    capa.innerHTML += `Descuento: ${articulo.dto_venta}<br>`;
    capa.innerHTML += `Und. Vendidas: ${articulo.und_vendidas}<br>`;
    capa.innerHTML += `Und. Disponibles: ${articulo.und_disponibles}<br>`;
    
    if( articulo.fecha_disponible ) {
        let milisegundos = Date.parse(articulo.fecha_disponible);
        let fecha = new Date(milisegundos);
        const opciones_fecha = { year: "numeric", month: "2-digit", day: "2-digit"};
        let fecha_disponible = fecha.toLocaleString(undefined, opciones_fecha);
        capa.innerHTML += `Fecha disponible: ${fecha_disponible}<br>`;
    }
    else {
        capa.innerHTML += `Fecha disponible: sin disponibilidad<br>`;
    }
    capa.innerHTML += `Categoría: ${articulo.categoria}<br>`;
    capa.innerHTML += `Tipo iva: ${articulo.tipo_iva}</p>`;
}