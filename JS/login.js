// Login: envia usuario y clave a PHP. La sesion vive en el servidor (cookie de sesion PHP).
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('formLogin');
    const mensaje = document.getElementById('mensaje-error');

    formulario.addEventListener('submit', async (event) => {
        event.preventDefault();
        mensaje.innerText = '';

        const usuario = document.getElementById('usuario').value.trim();
        const clave = document.getElementById('clave').value;
        if (usuario === '' || clave === '') {
            mensaje.innerText = 'Completa usuario y contraseña.';
            return;
        }

        const datos = new FormData();
        datos.append('usuario', usuario);
        datos.append('clave', clave);

        try {
            const response = await fetch('../negocio/login.php', { method: 'POST', body: datos });
            const resultado = await response.json();
            if (!resultado.ok) {
                mensaje.innerText = resultado.error;
                return;
            }
            window.location.href = 'admin.html';
        } catch (error) {
            console.error('Error en la peticion:', error);
            mensaje.innerText = 'No se pudo conectar con el servidor.';
        }
    });
});
