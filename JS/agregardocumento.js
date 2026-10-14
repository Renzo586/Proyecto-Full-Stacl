// Formulario de alta de documentos. La validacion real se hace en PHP; aca solo se muestran sus mensajes.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-documento');
    const mensaje = document.getElementById('mensaje-estado');

    // Si la sesion trae el id del administrativo, el campo se completa solo.
    const id = localStorage.getItem('id');
    if (id) {
        document.getElementById('idAdmin').value = id;
    }

    formulario.addEventListener('submit', function (event) {
        event.preventDefault();
        mensaje.textContent = '';

        fetch('../negocio/creardocumento.php', { method: 'POST', body: new FormData(this) })
            .then((response) => response.json())
            .then((data) => {
                if (data.ok) {
                    mensaje.textContent = data.mensaje;
                    mensaje.className = 'mensaje-ok';
                    const idAdmin = document.getElementById('idAdmin').value;
                    this.reset();
                    document.getElementById('idAdmin').value = idAdmin; // se conserva para el proximo
                } else {
                    mensaje.textContent = 'Error: ' + data.error;
                    mensaje.className = 'mensaje-error';
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                mensaje.textContent = 'No se pudo conectar con el servidor.';
                mensaje.className = 'mensaje-error';
            });
    });
});
