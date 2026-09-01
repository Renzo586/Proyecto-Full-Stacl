<?php
require_once __DIR__ . '/../datos/conexion.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Content-Type: application/json');

try {
    $codigoQR = $_POST['codigoQR'] ?? '';

    if (empty($codigoQR)) {
        echo json_encode(['ok' => false, 'error' => 'Falta el código QR del documento a eliminar']);
        exit;
    }

    $db = new Documentobd();
    $resultado = $db->eliminarDocumento($codigoQR);

    echo json_encode([
        'ok' => $resultado,
        'mensaje' => $resultado ? 'Documento eliminado correctamente' : null,
        'error' => $resultado ? null : 'No se encontró un documento con ese código'
    ]);
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
}