<?php
// Login: valida usuario y contrasena con password_verify() y crea la sesion PHP.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/Usuariobd.php';

exigirMetodo('POST');
sesion_iniciar();

$usuario = campoPost('usuario');
$clave   = $_POST['clave'] ?? '';

$errores = [];
if ($usuario === '') {
    $errores[] = 'Escribi tu usuario.';
}
if ($clave === '') {
    $errores[] = 'Escribi tu contrasena.';
}
if ($errores) {
    responderErrores($errores);
}

$fila = (new Usuariobd())->buscarPorUsuario($usuario);

$mensajeError = 'Usuario o contrasena incorrectos.';

if ($fila === null) {
    responderErrores([$mensajeError]);
}
if ((int) $fila['Activo'] !== 1) {
    responderErrores([$mensajeError]);
}
if (!password_verify($clave, $fila['Clave_hash'])) {
    responderErrores([$mensajeError]);
}

session_regenerate_id(true);
$_SESSION['usuario'] = [
    'id'     => (int) $fila['Id_usuario'],
    'nombre' => $fila['Nombre'],
    'rol'    => $fila['Rol'],
];

responderJson(['ok' => true, 'nombre' => $fila['Nombre'], 'rol' => $fila['Rol']]);