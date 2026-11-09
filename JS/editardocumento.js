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
            if (!categorias.ok || !documentos.ok) {
                mostrar('Error: ' + (categorias.error || documentos.error), true);
                return;
            }
            const documento = documentos.documentos.find((d) => String(d.Id_documento) === String(idDocumento));
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
        .catch((error) => {
            console.error('Error al cargar el documento:', error);
            mostrar('No se pudo cargar el documento. Revisa la consola (F12) para ver el detalle.', true);
        });

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
            .catch((error) => {
                console.error('Error al guardar:', error);
                mostrar('No se pudo guardar. Revisa la consola (F12) para ver el detalle.', true);
            });
    });
});