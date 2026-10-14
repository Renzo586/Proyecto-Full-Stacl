// Formulario de registro de pacientes. La validacion se hace en PHP aca se muestran sus mensajes.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-registro');
    const mensaje = document.getElementById('mensaje-estado');
    if (!formulario) return;

    formulario.addEventListener('submit', function (event) {
        event.preventDefault();
        mensaje.textContent = '';

        fetch('../negocio/registro.php', { method: 'POST', body: new FormData(this) })
            .then((response) => response.json())
            .then((data) => {
                if (data.ok) {
                    mensaje.textContent = data.mensaje;
                    mensaje.className = 'mensaje-ok';
                    this.reset();
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
