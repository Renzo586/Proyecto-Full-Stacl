// Login: envia la credencial a PHP y, si es valida, guarda la sesion en LocalStorage.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('formLogin');
    const mensaje = document.getElementById('mensaje-error');

    formulario.addEventListener('submit', async (event) => {
        event.preventDefault();
        mensaje.innerText = '';

        const datos = new FormData();
        datos.append('credencial', document.getElementById('usuario').value);

        try {
            const response = await fetch('../negocio/login.php', { method: 'POST', body: datos });
            const resultado = await response.json();

            if (!resultado.ok) {
                mensaje.innerText = resultado.error;
                return;
            }
            localStorage.setItem('nombre', resultado.nombre);
            localStorage.setItem('rol', resultado.rol);
            localStorage.setItem('token', resultado.token);
            if (resultado.id) {
                localStorage.setItem('id', resultado.id);
            }

            window.location.href = resultado.rol === 'admin' ? 'admin.html' : 'paciente.html';
        } catch (error) {
            console.error('Error en la peticion:', error);
            mensaje.innerText = 'No se pudo conectar con el servidor.';
        }
    });
});
