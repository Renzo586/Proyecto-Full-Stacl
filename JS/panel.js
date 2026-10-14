// Muestra el nombre del usuario en los paneles de admin y de paciente.
document.addEventListener('DOMContentLoaded', () => {
    const saludo = document.getElementById('saludo-usuario');
    const nombre = localStorage.getItem('nombre');

    if (saludo && nombre) {
        saludo.textContent = nombre;
    }
});
