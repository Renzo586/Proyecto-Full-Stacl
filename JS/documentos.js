// Listado de documentos (tabla HTML). La baja se hace con un formulario por fila.
document.addEventListener('DOMContentLoaded', () => {
    cargarDocumentos();
});

function mostrarMensaje(texto, esError) {
    const mensaje = document.getElementById('mensaje-estado');
    mensaje.textContent = texto;
    mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';
}

function cargarDocumentos() {
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
                tbody.innerHTML = '<tr><td colspan="5">Todavia no hay documentos cargados.</td></tr>';
                return;
            }

            data.documentos.forEach((documento) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${documento.CodigoQR}</td>
                        <td>${documento.Titulo}</td>
                        <td>${documento.Descripcion ?? ''}</td>
                        <td>${documento.Id_administrativo}</td>
                        <td>
                            <a href="verdocumento.html?codigo=${documento.CodigoQR}" target="_blank">Ver</a>
                            <form method="POST" action="../negocio/eliminardocumento.php" style="display:inline" onsubmit="return confirm('Seguro que queres eliminar este documento?')">
                                <input type="hidden" name="codigoQR" value="${documento.CodigoQR}">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>`;
            });
        })
        .catch((error) => {
            console.error('Error:', error);
            mostrarMensaje('No se pudo conectar con el servidor.', true);
        });
}
