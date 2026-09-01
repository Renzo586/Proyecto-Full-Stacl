<?php
require_once __DIR__ . '/../datos/conexion.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $codigoQR = $_POST['codigoQR'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $idAdmin = $_POST['idAdmin'] ?? '';

        $errores = [];
        if (empty($codigoQR)) {
            $errores[] = 'El código QR es obligatorio.';
        }
        if (empty($titulo)) {
            $errores[] = 'El título es obligatorio.';
        }
        if (empty($idAdmin) || !is_numeric($idAdmin)) {
            $errores[] = 'El id de administrativo debe ser un número válido.';
        }

        if (!empty($errores)) {
            echo json_encode(['ok' => false, 'error' => implode(' ', $errores)]);
            exit;
        }

        $db = new Documentobd();
        $resultado = $db->crearDocumento($codigoQR, $titulo, $descripcion, $idAdmin);

        echo json_encode([
            'ok' => (bool)$resultado,
            'mensaje' => $resultado ? 'Documento creado con éxito' : null,
            'error' => $resultado ? null : 'Ese código QR ya está registrado'
        ]);
    }
} catch (\Throwable $e) {
    echo json_encode(['ok' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);
}