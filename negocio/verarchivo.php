<?php
// Entrega el archivo de un documento a partir de su codigo QR (vista publica del paciente).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/documentobd.php';

$codigoQR  = campoGet('codigo');
$documento = $codigoQR === '' ? null : (new Documentobd())->obtenerPorCodigo($codigoQR);
if (!$documento) {
    http_response_code(404);
    exit('Documento no encontrado.');
}
$archivo = __DIR__ . '/../uploads/documentos/' . basename($documento['Ruta_archivo']);
if (!is_file($archivo)) {
    http_response_code(404);
    exit('El archivo ya no esta disponible.');
}

header('Content-Type: ' . mime_content_type($archivo));
readfile($archivo);
exit;