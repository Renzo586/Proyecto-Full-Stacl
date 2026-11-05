// Edicion de documento: carga los datos actuales y envia los cambios.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-editar');
    const mensaje = document.getElementById('mensaje-estado');
    const select = document.getElementById('idCategoria');
    const idDocumento = new URLSearchParams(window.location.search).get('id');

    const mostrar = (texto, esError) => {
        mensaje.textContent = texto;
        mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';
    };
    Promise.all([
        fetch('../negocio/listarcategorias.php').then((response) => response.json()),
        fetch('../negocio/listardocumentos.php').then((response) => response.json()),
    ])
        .then(([categorias, documentos]) => {
            const documento = documentos.documentos.find((d) => d.Id_documento === idDocumento);
            if (!documento) {
                mostrar('No se encontro el documento.', true);
                formulario.hidden = true;
                return;
            }
            categorias.categorias.forEach((cat) => {
                const opcion = document.createElement('option');
                opcion.value = cat.Id_categoria;
                opcion.textContent = cat.Nombre;
                select.appendChild(opcion);
            });

            document.getElementById('id').value = documento.Id_documento;
            document.getElementById('codigo-qr').textContent = documento.Codigo_qr;
            document.getElementById('titulo').value = documento.Titulo;
            document.getElementById('descripcion').value = documento.Descripcion || '';
            select.value = documento.Id_categoria;
        })
        .catch(() => mostrar('No se pudo conectar con el servidor.', true));

    formulario.addEventListener('submit', (event) => {
        event.preventDefault();
        mensaje.textContent = '';

        if (document.getElementById('titulo').value.trim() === '') {
            mostrar('El titulo es obligatorio.', true);
            return;
        }
        fetch('../negocio/editardocumento.php', { method: 'POST', body: new FormData(formulario) })
            .then((response) => response.json())
            .then((data) => mostrar(data.ok ? data.mensaje : 'Error: ' + data.error, !data.ok))
            .catch(() => mostrar('No se pudo conectar con el servidor.', true));
    });
});