<?php
// Registro de pacientes con validacion en el backend. Requiere sesion (administrador u operador).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/Usuariobd.php';

exigirMetodo('POST');
sesion_requerir_rol('administrador', 'operador');

$nombre   = campoPost('nombre');
$apellido = campoPost('apellido');
$cedula   = campoPost('cedula');
$telefono = campoPost('telefono');

$errores = [];
if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio.';
}
if ($apellido === '') {
    $errores[] = 'El apellido es obligatorio.';
}
if ($cedula === '') {
    $errores[] = 'La cedula es obligatoria.';
}
if ($telefono === '') {
    $errores[] = 'El telefono es obligatorio.';
}
if ($errores) {
    responderErrores($errores);
}

$registrado = (new Usuariobd())->registrarPaciente($nombre, $apellido, $cedula, $telefono);
if (!$registrado) {
    responderErrores(['Ya existe un paciente registrado con esa cedula.']);
}

responderJson(['ok' => true, 'mensaje' => 'Paciente registrado con exito.']);