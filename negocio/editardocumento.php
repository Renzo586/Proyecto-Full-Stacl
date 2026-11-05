<?php
// Edicion de un documento (titulo, descripcion y categoria). Administrador u operador.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/documentobd.php';

exigirMetodo('POST');
sesion_requerir_rol('administrador', 'operador');

$id          = (int) campoPost('id');
$titulo      = campoPost('titulo');
$descripcion = campoPost('descripcion');
$idCategoria = (int) campoPost('idCategoria');

$errores = [];
if ($id <= 0) {
    $errores[] = 'Documento invalido.';
}
if ($titulo === '' || strlen($titulo) > 150) {
    $errores[] = 'El titulo es obligatorio (maximo 150 caracteres).';
}
if (strlen($descripcion) > 1000) {
    $errores[] = 'La descripcion no puede superar los 1000 caracteres.';
}
if ($idCategoria <= 0) {
    $errores[] = 'Elegi una categoria.';
}
if ($errores) {
    responderErrores($errores);
}
$documentos = new Documentobd();
if ($documentos->obtenerPorId($id) === null) {
    responderErrores(['El documento no existe.']);
}
try {
    $documentos->actualizarDocumento($id, $titulo, $descripcion === '' ? null : $descripcion, $idCategoria);
} catch (PDOException $e) {
    responderErrores(['No se pudo guardar. Revisa que la categoria sea valida.']);
}

responderJson(['ok' => true, 'mensaje' => 'Documento actualizado con exito.']);