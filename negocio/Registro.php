<?php
// Registro de pacientes con validacion simple en el backend.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/Usuariobd.php';

header('Access-Control-Allow-Methods: POST');

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
