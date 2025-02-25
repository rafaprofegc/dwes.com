function registra_eventos() {
    botonEnviar = document.getElementById("enviar");
    botonEnviar.addEventListener("click", presentaArticulo);
}

function presentaArticulo() {

    const cuadro_referencia = document.getElementById("referencia");
    if( cuadro_referencia.value == "" ) {
        alert("Hay que indicar una referencia de artículo");
        return;
    }

    // Tenemos una referencia de artículo
    const cuerpo = {
        jsonrpc: "2.0",
        method: "RpcOrmArticulo.obtenerArticulo",
        params: [cuadro_referencia.value],
        id: 1234
    };

    const peticion = {
        method: "POST",
        headers: {Accept: "application/json"},
        body: JSON.stringify(cuerpo)
    };

    fetch("http://dwes.com/ra7/rpc/index.php", peticion)
    .then( respuesta => {
        if( !respuesta.ok ) throw new Error("Error: " + respuesta.status);

        return respuesta.json();
    })
    .then( respuesta_json => {
        if( respuesta_json.error ) new Error("Error de servidor: " + 
                                          respuesta_json.error.code + " " + respuesta_json.error.message);
        presentarDatos(respuesta_json.result);
    })
    .catch( error => {
        console.log(error.message);
    });
}

function presentarDatos(articulo) {
    const capa = document.getElementById("resultado");
    // capa.innerHTML = "";
    while( capa.hasChildNodes() ) capa.removeChild(capa.firstChild);

    /*
    <h3>Datos del artículo</h3>
    <p>
        Referencia: ACIN0001<br>
        Descripción: .... <br>
    </p>
    */

    const h3 = document.createElement("h3");
    const texto_h3 = document.createTextNode("Datos del artículo");
    h3.appendChild(texto_h3);
    capa.appendChild(h3);

    let p = document.createElement("p");
    let br = document.createElement("br");

    const ref = document.createElement("span");
    const texto_ref = document.createTextNode(`Referencia: ${articulo.referencia}`);
    ref.appendChild(texto_ref);
    p.appendChild(ref);
    p.appendChild(br);

    const desc = document.createElement("span");
    const texto_desc = document.createTextNode(`Descripción: ${articulo.descripcion}`);
    br = document.createElement("br");
    desc.appendChild(texto_desc);
    p.appendChild(desc);
    p.appendChild(br);

    const pvp = document.createElement("span");
    const texto_pvp = document.createTextNode(`PVP: ${articulo.pvp}€`);
    br = document.createElement("br");
    pvp.appendChild(texto_pvp);
    p.appendChild(pvp);
    p.appendChild(br);

    const dto = document.createElement("span");
    const texto_dto = document.createTextNode(`Descuento: ${parseFloat(articulo.dto_venta) * 100}%`);
    br = document.createElement("br");
    dto.appendChild(texto_dto);
    p.appendChild(dto);
    p.appendChild(br);

    const und_vend = document.createElement("span");
    const texto_uv = document.createTextNode(`Und. vendidas: ${articulo.und_vendidas}`);
    br = document.createElement("br");
    und_vend.appendChild(texto_uv);
    p.appendChild(und_vend);
    p.appendChild(br);

    const und_disp = document.createElement("span");
    const texto_ud = document.createTextNode(`Und. disponibles: ${articulo.und_disponibles}`);
    br = document.createElement("br");
    und_disp.appendChild(texto_ud);
    p.appendChild(und_disp);
    p.appendChild(br);

    let fecha_disponible = "Sin disponibilidad";
    if( articulo.fecha_disponible ) {
        const fecha = new Date( Date.parse(articulo.fecha_disponible));
        const opciones_fecha = { year: "numeric", month: "2-digit", day: "2-digit"};
        fecha_disponible = fecha.toLocaleString("es-ES", opciones_fecha);
    }

    const fecha_disp = document.createElement("span");
    const texto_fd = document.createTextNode(`Fecha disponibilidad: ${fecha_disponible}`);
    br = document.createElement("br");
    fecha_disp.appendChild(texto_fd);
    p.appendChild(fecha_disp);
    p.appendChild(br);

    const categoria = document.createElement("span");
    const texto_ca = document.createTextNode(`Categoria: ${articulo.categoria}`);
    br = document.createElement("br");
    categoria.appendChild(texto_ca);
    p.appendChild(categoria);
    p.appendChild(br);

    const tipo_iva = document.createElement("span");
    const texto_iva = document.createTextNode(`Tipo IVA: ${articulo.tipo_iva}`);
    br = document.createElement("br");
    tipo_iva.appendChild(texto_iva);
    p.appendChild(tipo_iva);
    p.appendChild(br);
    
    capa.appendChild(p);
}
