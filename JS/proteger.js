// Proteccion de rutas. Se incluye en el <head> de cada pagina privada:
//   <script src="../JS/proteger.js" data-roles="admin"></script>
(() => {
    const script = document.currentScript;
    const rolesPermitidos = script.dataset.roles;

    const verificar = () => {
        const token = localStorage.getItem('token');
        const rol = localStorage.getItem('rol');

        // Sin sesion -> al login.
        if (!token || !rol) {
            localStorage.clear();
            window.location.href = 'login.html';
            return;
        }
        // Con sesion pero con otro rol -> a su propio panel.
        if (rolesPermitidos && !rolesPermitidos.includes(rol)) {
            window.location.href = rol === 'admin' ? 'admin.html' : 'paciente.html';
        }
    };

    verificar();

    // Si el navegador restaura la pagina con el boton "Atras", se vuelve a verificar.
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) verificar();
    });
})();
