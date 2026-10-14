<?php
// Baja de un documento: llega desde un formulario de la tabla y vuelve a la lista.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Access-Control-Allow-Methods: POST');

$codigoQR = campoPost('codigoQR');

(new Documentobd())->eliminarDocumento($codigoQR);

header('Location: ../Visual/documentos.html');
exit;
