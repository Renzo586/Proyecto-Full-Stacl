// Cierre de sesion: borra la sesion de LocalStorage y vuelve al login.
document.addEventListener('DOMContentLoaded', () => {
    const btnLogout = document.getElementById('btn-logout');
    if (!btnLogout) return;

    // Si hay sesion iniciada, se muestra el boton (en las paginas publicas empieza oculto).
    if (localStorage.getItem('token')) {
        btnLogout.style.display = '';
    }

    btnLogout.addEventListener('click', (event) => {
        event.preventDefault();
        localStorage.clear();
        window.location.href = 'login.html';
    });
});
