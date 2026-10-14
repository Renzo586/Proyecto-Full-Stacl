<?php
// Alta de documentos con validacion simple en el backend.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Access-Control-Allow-Methods: POST');

$codigoQR    = campoPost('codigoQR');
$titulo      = campoPost('titulo');
$descripcion = campoPost('descripcion');
$idAdmin     = campoPost('idAdmin');

$errores = [];
if ($codigoQR === '') {
    $errores[] = 'El codigo QR es obligatorio.';
}
if ($titulo === '') {
    $errores[] = 'El titulo es obligatorio.';
}
if ($idAdmin === '') {
    $errores[] = 'El id de administrativo es obligatorio.';
}
if ($errores) {
    responderErrores($errores);
}

$creado = (new Documentobd())->crearDocumento($codigoQR, $titulo, $descripcion, $idAdmin);
if (!$creado) {
    responderErrores(["Ya existe un documento con el codigo QR \"$codigoQR\"."]);
}

responderJson(['ok' => true, 'mensaje' => 'Documento creado con exito.']);
