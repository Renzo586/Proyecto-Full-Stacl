<?php
require_once __DIR__ . '/../datos/conexion.php';
require_once __DIR__ . '/../datos/Usuariobd.php';
header('Content-Type: application/json');
try {
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$cedula = $_POST['cedula'] ?? '';
$telefono = $_POST['telefono'] ?? '';
if (empty($nombre) || empty($cedula)) {
echo json_encode(['ok' => false, 'error' => 'Faltan datos obligatorios']);
exit;}
$db = new Usuariobd();
$resultado = $db->registrarPaciente($nombre, $apellido, $cedula, $telefono);
echo json_encode([
'ok' => (bool)$resultado,
'mensaje' => $resultado ? 'Paciente registrado con éxito' : null,
'error' => $resultado ? null : 'La cédula ya está registrada o hubo un error'
]);
}} 
catch (\Throwable $e) {
echo json_encode(['ok' => false, 'error' => 'Error del servidor: ' . $e->getMessage()]);}