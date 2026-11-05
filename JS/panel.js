// Muestra el nombre del usuario logueado en el panel.
const mostrarSaludo = (usuario) => {
    const saludo = document.getElementById('saludo-usuario');
    if (saludo) saludo.textContent = usuario.nombre;
};
document.addEventListener('sesion-lista', (e) => mostrarSaludo(e.detail));
if (window.USUARIO_ACTUAL) mostrarSaludo(window.USUARIO_ACTUAL);
