document.addEventListener('DOMContentLoaded', () => {
    const btnLogout = document.getElementById('btn-logout');

    if (btnLogout) {
        btnLogout.addEventListener('click', (e) => {
e.preventDefault();
localStorage.removeItem('usuario');
window.location.href = 'login.html';
});
}
});