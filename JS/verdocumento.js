// Vista que se abre al escanear el QR: lee ?codigo=... de la URL y muestra los datos del documento.
document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('detalle-documento');
    const codigo = window.location.search.replace('?codigo=', '');

    if (codigo === '') {
        contenedor.textContent = 'No se especifico ningun codigo de documento en la direccion.';
        return;
    }

    // Agrega una linea "Etiqueta: valor" usando texto plano.
    const agregarLinea = (etiqueta, valor) => {
        const parrafo = document.createElement('p');
        const negrita = document.createElement('strong');
        negrita.textContent = etiqueta + ': ';
        parrafo.appendChild(negrita);
        parrafo.appendChild(document.createTextNode(valor));
        contenedor.appendChild(parrafo);
    };

    fetch('../negocio/verdocumento.php?codigo=' + codigo)
        .then((response) => response.json())
        .then((data) => {
            contenedor.textContent = '';
            if (!data.ok) {
                contenedor.textContent = 'Error: ' + data.error;
                return;
            }
            const documento = data.documento;
            const titulo = document.createElement('h3');
            titulo.textContent = documento.Titulo;
            contenedor.appendChild(titulo);
            agregarLinea('Codigo QR', documento.CodigoQR);
            agregarLinea('Descripcion', documento.Descripcion || 'Sin descripcion');
        })
        .catch((error) => {
            console.error('Error:', error);
            contenedor.textContent = 'No se pudo conectar con el servidor.';
        });
});
