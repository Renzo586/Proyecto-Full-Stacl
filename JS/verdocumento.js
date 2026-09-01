document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const codigo = params.get('codigo');

    const contenedor = document.getElementById('detalle-documento');

    if (!codigo) {
        contenedor.innerText = 'No se especificó ningún código de documento en la URL.';
        return;
    }

    fetch(`../negocio/verdocumento.php?codigo=${encodeURIComponent(codigo)}`)
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                const doc = data.documento;
                contenedor.innerHTML = `
                    <h3>${doc.Titulo}</h3>
                    <p><strong>Código QR:</strong> ${doc.CodigoQR}</p>
                    <p><strong>Descripción:</strong> ${doc.Descripcion ?? 'Sin descripción'}</p>
                `;
            } else {
                contenedor.innerText = 'Error: ' + data.error;
            }
        })
        .catch(err => {
            console.error('Error:', err);
            contenedor.innerText = 'No se pudo conectar con el servidor.';
        });
});