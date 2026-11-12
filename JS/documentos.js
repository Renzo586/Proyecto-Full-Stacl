// Listado de documentos: ver, editar, mostrar QR y eliminar. Se carga cuando la sesion esta verificada.
const mostrarMensaje = (texto, esError) => {
    const mensaje = document.getElementById('mensaje-estado');
    mensaje.textContent = texto;
    mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';
};

const celda = (texto) => {
    const td = document.createElement('td');
    td.textContent = texto ?? '';
    return td;
};
const mostrarQR = (doc) => {
    const contenedor = document.getElementById('qr-contenedor');
    contenedor.innerHTML = '';
    const enlace = new URL('verdocumento.html?codigo=' + encodeURIComponent(doc.Codigo_qr), window.location.href).href;
    const titulo = document.createElement('h3');
    titulo.textContent = 'QR de: ' + doc.Titulo;
    const imagen = document.createElement('div');
    new QRCode(imagen, { text: enlace, width: 200, height: 200 });
    const texto = document.createElement('p');
    texto.textContent = enlace;

    contenedor.append(titulo, imagen, texto);
};

const eliminarDocumento = (id, usuario) => {
    if (!confirm('Seguro que queres eliminar este documento?')) return;
    const datos = new FormData();
    datos.append('id', id);
    fetch('../negocio/Eliminardocumento.php', { method: 'POST', body: datos })
        .then((response) => response.json())
        .then((data) => {
            mostrarMensaje(data.ok ? data.mensaje : 'Error: ' + data.error, !data.ok);
            if (data.ok) cargarDocumentos(usuario);
        })
        .catch(() => mostrarMensaje('No se pudo conectar con el servidor.', true));
};

const cargarDocumentos = (usuario) => {
    fetch('../negocio/listardocumentos.php')
        .then((response) => response.json())
        .then((data) => {
            if (!data.ok) {
                mostrarMensaje('Error: ' + data.error, true);
                return;
            }
            const tbody = document.querySelector('#tabla-documentos tbody');
            tbody.innerHTML = '';

            if (data.documentos.length === 0) {
                const tr = document.createElement('tr');
                const td = celda('Todavia no hay documentos cargados.');
                td.colSpan = 5;
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }

            data.documentos.forEach((doc) => {
                const tr = document.createElement('tr');
                tr.appendChild(celda(doc.Codigo_qr));
                tr.appendChild(celda(doc.Titulo));
                tr.appendChild(celda(doc.Descripcion));
                tr.appendChild(celda(doc.Categoria));
                const acciones = document.createElement('td');
                const ver = document.createElement('a');
                ver.href = 'verdocumento.html?codigo=' + encodeURIComponent(doc.Codigo_qr);
                ver.target = '_blank';
                ver.textContent = 'Ver';
                const editar = document.createElement('a');
                editar.href = 'editardocumento.html?id=' + doc.Id_documento;
                editar.textContent = 'Editar';
                const botonQR = document.createElement('button');
                botonQR.type = 'button';
                botonQR.textContent = 'QR';
                botonQR.addEventListener('click', () => mostrarQR(doc));
                acciones.append(ver, editar, botonQR);
                if (usuario.rol === 'administrador') {
                    const eliminar = document.createElement('button');
                    eliminar.type = 'button';
                    eliminar.textContent = 'Eliminar';
                    eliminar.addEventListener('click', () => eliminarDocumento(doc.Id_documento, usuario));
                    acciones.appendChild(eliminar);
                }
                tr.appendChild(acciones);
                tbody.appendChild(tr);
            });
        })
        .catch((error) => {
            console.error('Error:', error);
            mostrarMensaje('No se pudo conectar con el servidor.', true);
        });
};
document.addEventListener('sesion-lista', (e) => cargarDocumentos(e.detail));
if (window.USUARIO_ACTUAL) cargarDocumentos(window.USUARIO_ACTUAL);