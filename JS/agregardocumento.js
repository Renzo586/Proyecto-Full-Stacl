document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-documento');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const datos = new FormData(this);

        fetch('../negocio/CrearDocumento.php', {
            method: 'POST',
            body: datos
        })
            .then(res => res.json())
            .then(data => {
                const mensaje = document.getElementById('mensaje-estado');
                if (data.ok) {
                    mensaje.innerText = data.mensaje;
                    this.reset();
                } else {
                    mensaje.innerText = 'Error: ' + data.error;
                }
            })
            .catch(err => console.error('Error:', err));
    });
});