<?php
// Devuelve los datos de un documento a partir del codigo QR (vista publica, sin sesion).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/documentobd.php';

exigirMetodo('GET');

$codigoQR = campoGet('codigo');
if ($codigoQR === '') {
    responderErrores(['Falta el codigo del documento en la direccion.']);
}

$documento = (new Documentobd())->obtenerPorCodigo($codigoQR);
if (!$documento) {
    responderErrores(["No se encontro ningun documento con el codigo \"$codigoQR\"."]);
}

responderJson(['ok' => true, 'documento' => $documento]);
