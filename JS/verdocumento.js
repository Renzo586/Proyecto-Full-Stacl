// Vista publica (se abre al escanear el QR): muestra los datos del documento y un enlace al archivo.
document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('detalle-documento');
    const codigo = new URLSearchParams(window.location.search).get('codigo') || '';

    if (codigo === '') {
        contenedor.textContent = 'No se especifico ningun codigo de documento en la direccion.';
        return;
    }
    const agregarLinea = (etiqueta, valor) => {
        const parrafo = document.createElement('p');
        const negrita = document.createElement('strong');
        negrita.textContent = etiqueta + ': ';
        parrafo.appendChild(negrita);
        parrafo.appendChild(document.createTextNode(valor));
        contenedor.appendChild(parrafo);
    };

    fetch('../negocio/verdocumento.php?codigo=' + encodeURIComponent(codigo))
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

            agregarLinea('Codigo QR', documento.Codigo_qr);
            agregarLinea('Categoria', documento.Categoria);
            agregarLinea('Descripcion', documento.Descripcion || 'Sin descripcion');

            const enlace = document.createElement('a');
            enlace.href = '../negocio/verarchivo.php?codigo=' + encodeURIComponent(documento.Codigo_qr);
            enlace.textContent = 'Abrir documento';
            enlace.target = '_blank';
            contenedor.appendChild(enlace);
        })
        .catch((error) => {
            console.error('Error:', error);
            contenedor.textContent = 'No se pudo conectar con el servidor.';
        });
});