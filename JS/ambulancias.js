// Modulo de ambulancias: alta, edicion, cambio de estado e historial con filtros.
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-traslado');
    const formFiltro = document.getElementById('form-filtro');
    const mensaje = document.getElementById('mensaje-estado');
    const tbody = document.querySelector('#tabla-traslados tbody');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnCancelarEdicion = document.getElementById('btn-cancelar-edicion');

    const ids = ['id', 'paciente', 'matricula', 'chofer', 'enfermero', 'origen', 'destino', 'salida', 'llegada', 'observaciones'];
    const campos = {};
    ids.forEach((nombre) => { campos[nombre] = document.getElementById(nombre); });

    // A que estados se puede pasar desde cada uno, y el texto de cada boton.
    const ACCIONES = {
        'Pendiente': ['En curso', 'Cancelado'],
        'En curso': ['Finalizado', 'Cancelado'],
        'Finalizado': [],
        'Cancelado': [],
    };
    const ETIQUETAS = { 'En curso': 'Iniciar', 'Finalizado': 'Finalizar', 'Cancelado': 'Cancelar' };

    const mostrar = (texto, esError) => {
        mensaje.textContent = texto;
        mensaje.className = esError ? 'mensaje-error' : 'mensaje-ok';
    };

    const celda = (texto) => {
        const td = document.createElement('td');
        td.textContent = texto ?? '';
        return td;
    };

    // Llena un select con una lista. "armarTexto" decide que texto se muestra en cada opcion.
    const llenarSelect = (select, lista, campoValor, armarTexto, textoVacio) => {
        select.innerHTML = '';
        const vacia = document.createElement('option');
        vacia.value = '';
        vacia.textContent = textoVacio;
        select.appendChild(vacia);
        lista.forEach((item) => {
            const opcion = document.createElement('option');
            opcion.value = item[campoValor];
            opcion.textContent = armarTexto(item);
            select.appendChild(opcion);
        });
    };

    const cargarOpciones = () => {
        fetch('../negocio/opcionestraslado.php')
            .then((response) => response.json())
            .then((data) => {
                if (!data.ok) return mostrar('Error: ' + data.error, true);
                const o = data.opciones;
                llenarSelect(campos.paciente, o.pacientes, 'Cedula', (p) => p.Nombre + ' (' + p.Cedula + ')', 'Elegi un paciente');
                llenarSelect(campos.matricula, o.ambulancias, 'Matricula', (a) => a.Matricula + ' - ' + a.Modelo, 'Elegi un movil');
                llenarSelect(campos.chofer, o.choferes, 'Cedula', (c) => c.Nombre, 'Elegi un chofer');
                llenarSelect(campos.enfermero, o.enfermeros, 'Cedula', (e) => e.Nombre, 'Elegi un enfermero');
                llenarSelect(document.getElementById('filtro-matricula'), o.ambulancias, 'Matricula', (a) => a.Matricula + ' - ' + a.Modelo, 'Todos los moviles');
            })
            .catch(() => mostrar('No se pudieron cargar las opciones.', true));
    };

    const limpiarFormulario = () => {
        formulario.reset();
        campos.id.value = '';
        btnGuardar.textContent = 'Registrar Traslado';
        btnCancelarEdicion.hidden = true;
    };

    // Pasa los datos de un traslado al formulario para editarlo.
    const editar = (t) => {
        campos.id.value = t.Id_traslado;
        campos.paciente.value = t.Cedula_paciente;
        campos.matricula.value = t.Matricula;
        campos.chofer.value = t.Cedula_chofer;
        campos.enfermero.value = t.Cedula_enfermero;
        campos.origen.value = t.Origen;
        campos.destino.value = t.Destino;
        campos.salida.value = t.H_salida.slice(0, 16).replace(' ', 'T');
        campos.llegada.value = t.H_llegada ? t.H_llegada.slice(0, 16).replace(' ', 'T') : '';
        campos.observaciones.value = t.Observaciones || '';
        btnGuardar.textContent = 'Guardar cambios';
        btnCancelarEdicion.hidden = false;
        window.scrollTo(0, 0);
    };

    const cambiarEstado = (id, estado) => {
        if (!confirm('Cambiar el estado a "' + estado + '"?')) return;
        const datos = new FormData();
        datos.append('id', id);
        datos.append('estado', estado);
        fetch('../negocio/cambiarestado.php', { method: 'POST', body: datos })
            .then((response) => response.json())
            .then((data) => {
                mostrar(data.ok ? data.mensaje : 'Error: ' + data.error, !data.ok);
                if (data.ok) cargarTraslados();
            })
            .catch(() => mostrar('No se pudo conectar con el servidor.', true));
    };

    const dibujarTabla = (traslados) => {
        tbody.innerHTML = '';
        const conteo = { 'Pendiente': 0, 'En curso': 0, 'Finalizado': 0, 'Cancelado': 0 };

        if (traslados.length === 0) {
            const tr = document.createElement('tr');
            const td = celda('No hay traslados para mostrar.');
            td.colSpan = 10;
            tr.appendChild(td);
            tbody.appendChild(tr);
        }

        traslados.forEach((t) => {
            conteo[t.Estado]++;
            const tr = document.createElement('tr');
            [t.Paciente, t.Origen, t.Destino, t.Matricula, t.Chofer, t.Enfermero,
                t.H_salida.slice(0, 16), t.H_llegada ? t.H_llegada.slice(0, 16) : '-', t.Estado]
                .forEach((texto) => tr.appendChild(celda(texto)));

            const acciones = document.createElement('td');
            if (ACCIONES[t.Estado].length > 0) {
                const botonEditar = document.createElement('button');
                botonEditar.type = 'button';
                botonEditar.textContent = 'Editar';
                botonEditar.addEventListener('click', () => editar(t));
                acciones.appendChild(botonEditar);
            }
            ACCIONES[t.Estado].forEach((estado) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.textContent = ETIQUETAS[estado];
                boton.addEventListener('click', () => cambiarEstado(t.Id_traslado, estado));
                acciones.appendChild(boton);
            });
            tr.appendChild(acciones);
            tbody.appendChild(tr);
        });

        document.getElementById('stat-pendientes').textContent = conteo['Pendiente'];
        document.getElementById('stat-encurso').textContent = conteo['En curso'];
        document.getElementById('stat-finalizados').textContent = conteo['Finalizado'];
        document.getElementById('stat-cancelados').textContent = conteo['Cancelado'];
    };

    const cargarTraslados = () => {
        const filtros = new URLSearchParams({
            paciente: document.getElementById('filtro-paciente').value.trim(),
            matricula: document.getElementById('filtro-matricula').value,
        });
        fetch('../negocio/listartraslados.php?' + filtros.toString())
            .then((response) => response.json())
            .then((data) => {
                if (!data.ok) return mostrar('Error: ' + data.error, true);
                dibujarTabla(data.traslados);
            })
            .catch(() => mostrar('No se pudo conectar con el servidor.', true));
    };

    // Validacion en el navegador (PHP vuelve a validar todo).
    const validar = () => {
        if (campos.paciente.value === '') return 'Elegi un paciente.';
        if (campos.matricula.value === '') return 'Elegi un movil.';
        if (campos.chofer.value === '') return 'Elegi un chofer.';
        if (campos.enfermero.value === '') return 'Elegi un enfermero.';
        if (campos.origen.value.trim() === '') return 'El origen es obligatorio.';
        if (campos.destino.value.trim() === '') return 'El destino es obligatorio.';
        if (campos.salida.value === '') return 'La hora de salida es obligatoria.';
        if (campos.llegada.value !== '' && campos.llegada.value < campos.salida.value) {
            return 'La llegada no puede ser anterior a la salida.';
        }
        return '';
    };

    formulario.addEventListener('submit', (event) => {
        event.preventDefault();
        mensaje.textContent = '';

        const problema = validar();
        if (problema) return mostrar(problema, true);

        fetch('../negocio/guardartraslado.php', { method: 'POST', body: new FormData(formulario) })
            .then((response) => response.json())
            .then((data) => {
                if (data.ok) {
                    mostrar(data.mensaje, false);
                    limpiarFormulario();
                    cargarTraslados();
                } else {
                    mostrar('Error: ' + data.error, true);
                }
            })
            .catch(() => mostrar('No se pudo conectar con el servidor.', true));
    });

    formFiltro.addEventListener('submit', (event) => {
        event.preventDefault();
        cargarTraslados();
    });

    document.getElementById('btn-limpiar-filtro').addEventListener('click', () => {
        formFiltro.reset();
        cargarTraslados();
    });
    btnCancelarEdicion.addEventListener('click', limpiarFormulario);
    cargarOpciones();
    cargarTraslados();
});