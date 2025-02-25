function registra_eventos() {
    const boton = document.getElementById("buscar");
    boton.addEventListener("click", presentaArticulos);
}

async function presentaArticulos() {

    // Obtengo la descripción de la entrada de usuario
    const descripcion = document.getElementById("descripcion");

    // Datos de la petición
    const peticion = {
        method: "GET",
        headers: { Accept: "application/json"}
    };

    // Añadimos la cadena de consulta (parámetros GET)
    const parametros = new URLSearchParams();
    parametros.append("descripcion", descripcion.value);

    try {
        // Se hace la petición con promesa
        const respuesta = await fetch(`http://dwes.com/articulos?${parametros}`, peticion);

        // Si hay error, lanza excepción
        if( !respuesta.ok ) 
            throw new Error(`Error en la respuesta: ${respuesta.status}: ${respuesta.statusText}`);

        // Se obtiene la respuesta en formato JSON
        respuesta_json = await respuesta.json();

        if( respuesta_json.exito ) {
            mostrarArticulos(respuesta_json.datos);
        }
        else {
            mostrarError(respuesta_json.error);
        }
    }
    catch( error ) {
        console.log(error.message);
    }

    
}

function mostrarArticulos( articulos ) {

    const tbody = document.getElementById("cuerpo");
    const plantilla = document.getElementById("fila_cuerpo");

    const opciones_fecha = { year: "numeric", month: "2-digit", day: "2-digit"};

    const tipos_iva = { N: "Normal", R: "Reducido", SR: "Superreducido"};

    while( tbody.hasChildNodes() ) tbody.removeChild(tbody.firstChild);

    articulos.forEach( articulo => {
        let fila = plantilla.content.cloneNode(true);
        let celdas = fila.querySelectorAll("td");
        celdas[0].textContent = `${articulo.referencia}`;
        celdas[1].textContent = `${articulo.descripcion}`;
        celdas[2].textContent = `${articulo.pvp}`;
        celdas[3].textContent = `${parseFloat(articulo.dto_venta) * 100}%`;
        celdas[4].textContent = `${articulo.und_vendidas}`;
        celdas[5].textContent = `${articulo.und_disponibles}`;
        if( articulo.fecha_disponible ) {
            let fecha = new Date(Date.parse(articulo.fecha_disponible));
            celdas[6].textContent = fecha.toLocaleString(undefined, opciones_fecha);
        }
        else {
            celdas[6].textContent = "Sin disponibilidad";
        }
        celdas[7].textContent = `${articulo.categoria}`;
        celdas[8].textContent = `${tipos_iva[articulo.tipo_iva]}`;
        
        tbody.appendChild(fila);
    });
}

function mostrarError( error ) {

    // Obtenemos la capa de error (añadida en 06articulos.php antes de la tabla)
    // y borramos sus elementos.
    let capaError = document.getElementById("error");
    while( capaError.hasChildNodes() ) capaError.removeChild(capaError.firstChild);

    // Creo elementos span para el código de error y el mensaje de error
    let codigoError = document.createElement("span");
    let codErrTxt = document.createTextNode(`Código de error: ${error[0]}`);
    codigoError.appendChild(codErrTxt);

    let mensajeError = document.createElement("span");
    let msjErrTxt = document.createTextNode(`Mensaje de error: ${error[1]}`);
    mensajeError.appendChild(msjErrTxt);

    // Añado los elementos span a la capa de error.
    capaError.appendChild(codigoError);
    capaError.appendChild( document.createElement("br"));
    capaError.appendChild(mensajeError);
}