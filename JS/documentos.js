document.addEventListener('DOMContentLoaded', () => {
    cargarDocumentos();
});

const cargarDocumentos = () => {
    fetch('../negocio/Listardocumentos.php')
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                renderizarTabla(data.documentos);
            } else {
                document.getElementById('mensaje-estado').innerText = 'Error: ' + data.error;
            }
        })
        .catch(err => console.error('Error:', err));
};

const renderizarTabla = (documentos) => {
    const tbody = document.querySelector('#tabla-documentos tbody');
    tbody.innerHTML = '';

    documentos.forEach((doc) => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${doc.CodigoQR}</td>
            <td>${doc.Titulo}</td>
            <td>${doc.Descripcion ?? ''}</td>
            <td>${doc.Id_administrativo}</td>
            <td><button class="btn-eliminar" data-codigo="${doc.CodigoQR}">Eliminar</button></td>
        `;
        tbody.appendChild(fila);
    });

    document.querySelectorAll('.btn-eliminar').forEach((boton) => {
        boton.addEventListener('click', (e) => eliminarDocumento(e.target.dataset.codigo));
    });
};

const eliminarDocumento = (codigoQR) => {
    const confirmado = confirm(`¿Seguro que quieres eliminar el documento "${codigoQR}"?`);
    if (!confirmado) return;

    const datos = new FormData();
    datos.append('codigoQR', codigoQR);

    fetch('../negocio/EliminarDocumento.php', {
        method: 'POST',
        body: datos
    })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                alert(data.mensaje);
                cargarDocumentos();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(err => console.error('Error:', err));
};