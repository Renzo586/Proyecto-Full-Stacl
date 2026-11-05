// Registro de pacientes: valida en el navegador y envia a PHP, que vuelve a validar.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-registro');
    const mensaje = document.getElementById('mensaje-estado');
    if (!formulario) return;

    const mostrar = (texto, esError) => {
        mensaje.textContent = texto;
        mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';
    };
    const validar = () => {
        const nombre = formulario.elements['nombre'].value.trim();
        const apellido = formulario.elements['apellido'].value.trim();
        const cedula = formulario.elements['cedula'].value.trim();
        const telefono = formulario.elements['telefono'].value.trim();

        if (nombre === '') return 'El nombre es obligatorio.';
        if (apellido === '') return 'El apellido es obligatorio.';
       if (isNaN(cedula) || cedula.length < 7 || cedula.length > 8) {
    return 'La cedula debe tener 7 u 8 numeros, sin puntos ni guion.';
}
    if (isNaN(telefono) || telefono.length < 8 || telefono.length > 9) {
    return 'El telefono debe tener 8 o 9 numeros.';
}
    };
    formulario.addEventListener('submit', (event) => {
        event.preventDefault();
        mensaje.textContent = '';

        const problema = validar();
        if (problema) {
            mostrar(problema, true);
            return;
        }

        fetch('../negocio/registro.php', { method: 'POST', body: new FormData(formulario) })
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
