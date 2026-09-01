<?php
require_once __DIR__ . '/../datos/conexion.php';

header('Content-Type: application/json');
$credencial = $_POST['credencial'] ?? '';
if (empty($credencial)) {
    echo json_encode(['ok' => false, 'error' => 'Escribe una credencial']);
    exit;
}
$pdo = obtenerConexion();
$stmt = $pdo->prepare("SELECT * FROM Administrativo WHERE Usuario = ?");
$stmt->execute([$credencial]);
$admin = $stmt->fetch();

if ($admin) {
    echo json_encode(['ok' => true, 'nombre' => $admin['Nombre'], 'rol' => 'admin']);
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM Paciente WHERE Cedula = ?");
$stmt->execute([$credencial]);
$paciente = $stmt->fetch();

if ($paciente) {
    echo json_encode(['ok' => true, 'nombre' => $paciente['Nombre'], 'rol' => 'paciente']);
    exit;
}
echo json_encode(['ok' => false, 'error' => 'Usuario no encontrado']);