(() => {
const usuarioGuardado = localStorage.getItem('usuario');
if (!usuarioGuardado) {
window.location.href = 'login.html';
return;}
const usuario = JSON.parse(usuarioGuardado);
console.log('Sesión activa:', usuario.nombre, '-', usuario.rol);
})();