// Encuesta anonima: valida en el navegador y envia a PHP.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-encuesta');
    const mensaje = document.getElementById('mensaje-estado');

    const mostrar = (texto, esError) => {
        mensaje.textContent = texto;
        mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';3
    };

    formulario.addEventListener('submit', (event) => {
        event.preventDefault();
        mensaje.textContent = '';
        const servicio = document.getElementById('servicio').value.trim();
        const calificacion = Number(document.getElementById('calificacion').value);
        const recomienda = document.querySelector('input[name="recomienda"]:checked');
        if (servicio === '') return mostrar('Escribi el servicio que utilizaste.', true);
        if (!Number.isInteger(calificacion) || calificacion < 1 || calificacion > 5) {
            return mostrar('La calificacion tiene que ser un numero del 1 al 5.', true);
        }
        if (!recomienda) return mostrar('Indica si recomendarias el servicio.', true);

        fetch('../negocio/guardarencuesta.php', { method: 'POST', body: new FormData(formulario) })
            .then((response) => response.json())
            .then((data) => {
                if (data.ok) {
                    mostrar(data.mensaje, false);
                    formulario.reset();
                } else {
                    mostrar('Error: ' + data.error, true);
                }
            })
            .catch(() => mostrar('No se pudo conectar con el servidor.', true));
    });
});