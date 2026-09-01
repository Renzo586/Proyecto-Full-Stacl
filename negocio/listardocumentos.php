<?php
require_once __DIR__ . '/../datos/conexion.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Content-Type: application/json');

try {
    $db = new Documentobd();
    $documentos = $db->listarDocumentos();

    echo json_encode([
        'ok' => true,
        'documentos' => $documentos
    ]);
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
}