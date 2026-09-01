<?php
require_once __DIR__ . '/../datos/conexion.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Content-Type: application/json');

try {
    $codigoQR = $_GET['codigo'] ?? '';

    if (empty($codigoQR)) {
        echo json_encode(['ok' => false, 'error' => 'Falta el código QR en la URL']);
        exit;
    }

    $db = new Documentobd();
    $documento = $db->obtenerPorCodigo($codigoQR);

    if ($documento) {
        echo json_encode(['ok' => true, 'documento' => $documento]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'No se encontró ningún documento con ese código']);
    }
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
}