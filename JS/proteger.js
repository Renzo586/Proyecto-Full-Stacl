(() => {
    const roles = (document.currentScript.dataset.roles || '')
        .split(',').map((r) => r.trim()).filter(Boolean);
    document.documentElement.style.visibility = 'hidden';

    const verificar = () => {
        fetch('../negocio/sesionactual.php')
            .then((response) => response.json())
            .then((data) => {
                if (!data.ok) {
                    window.location.href = 'login.html';
                    return;
                }
                if (roles.length && !roles.includes(data.rol)) {
                    window.location.href = 'admin.html';
                    return;
                }
                window.USUARIO_ACTUAL = data;
                document.documentElement.style.visibility = 'visible';
                document.dispatchEvent(new CustomEvent('sesion-lista', { detail: data }));
            })
            .catch(() => { window.location.href = 'login.html'; });
    };

    verificar();
    window.addEventListener('pageshow', (event) => { if (event.persisted) verificar(); });
})();
