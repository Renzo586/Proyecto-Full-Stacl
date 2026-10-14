<?php
// Login: valida la credencial (usuario de admin o cedula de paciente) y devuelve rol + clave de sesion.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/Usuariobd.php';

header('Access-Control-Allow-Methods: POST');

$credencial = campoPost('credencial');

if ($credencial === '') {
    responderErrores(['Escribi tu usuario o tu cedula.']);
}

$usuario = (new Usuariobd())->buscarUsuario($credencial);
if (!$usuario) {
    responderErrores(['No existe ningun usuario ni paciente con esa credencial.']);
}

responderJson([
    'ok'     => true,
    'nombre' => $usuario['nombre'],
    'rol'    => $usuario['rol'],
    'id'     => $usuario['id'],
    'token'  => uniqid(),
]);
