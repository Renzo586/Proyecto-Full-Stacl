// Cierre de sesion: destruye la sesion en el servidor y vuelve al login.
document.addEventListener('DOMContentLoaded', () => {
    const btnLogout = document.getElementById('btn-logout');
    if (!btnLogout) return;
    btnLogout.addEventListener('click', (event) => {
        event.preventDefault();
        fetch('../negocio/logout.php', { method: 'POST' })
            .finally(() => { window.location.replace('login.html'); });
    });
});