document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('formLogin').addEventListener('submit', async (e) => {
        e.preventDefault();
        const credencial = document.getElementById('usuario').value;
        const datos = new FormData();
        datos.append('credencial', credencial);

        try {
            const respuesta = await fetch('../negocio/login.php', {   // <-- este es el cambio
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            if (resultado.ok) {
                const usuario = {
                    nombre: resultado.nombre,
                    rol: resultado.rol
                };
                localStorage.setItem('usuario', JSON.stringify(usuario));

                if (resultado.rol === 'admin') {
                    window.location.href = 'admin.html';
                } else {
                    window.location.href = 'paciente.html';
                }
            } else {
                document.getElementById('mensaje-error').innerText = resultado.error;
            }
        } catch (error) {
            console.error('Error en la petición:', error);
            document.getElementById('mensaje-error').innerText = 'No se pudo conectar con el servidor.';
        }
    });
});