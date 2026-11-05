// Alta de documentos: valida en el navegador y envia el formulario con el archivo a PHP.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-documento');
    const mensaje = document.getElementById('mensaje-estado');
    const select = document.getElementById('idCategoria');
    const TAMANO_MAXIMO = 2 * 1024 * 1024; // 2 MB
    const EXTENSIONES = ['pdf', 'jpg', 'jpeg', 'png'];
    const mostrar = (texto, esError) => {
        mensaje.textContent = texto;
        mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';
    };
    fetch('../negocio/listarcategorias.php')
        .then((response) => response.json())
        .then((data) => {
            select.innerHTML = '<option value="">Elegi una categoria</option>';
            if (!data.ok) return;
            data.categorias.forEach((cat) => {
                const opcion = document.createElement('option');
                opcion.value = cat.Id_categoria;
                opcion.textContent = cat.Nombre;
                select.appendChild(opcion);
            });
        })
        .catch(() => mostrar('No se pudieron cargar las categorias.', true));
    const validar = () => {
        const codigo = document.getElementById('codigoQR').value.trim();
        const titulo = document.getElementById('titulo').value.trim();
        const archivo = document.getElementById('archivo').files[0];

        if (titulo === '') return 'El titulo es obligatorio.';
        if (select.value === '') return 'Elegi una categoria.';
        if (!archivo) return 'Selecciona un archivo.';

       const esValido = EXTENSIONES.reduce((acumulador, ext) => acumulador || archivo.name.slice(-ext.length) === ext, false);
    };

    formulario.addEventListener('submit', (event) => {
        event.preventDefault();
        mensaje.textContent = '';

        const problema = validar();
        if (problema) {
            mostrar(problema, true);
            return;
        }

        fetch('../negocio/creardocumento.php', { method: 'POST', body: new FormData(formulario) })
            .then((response) => response.json())
            .then((data) => {
                if (data.ok) {
                    mostrar(data.mensaje, false);
                    formulario.reset();
                } else {
                    mostrar('Error: ' + data.error, true);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                mostrar('No se pudo conectar con el servidor.', true);
            });
    });
});
