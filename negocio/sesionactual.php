<?php
// Devuelve los datos de la sesion activa (401 si no hay).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
$usuario = sesion_requerir_login();
responderJson(['ok' => true, 'id' => $usuario['id'], 'nombre' => $usuario['nombre'], 'rol' => $usuario['rol']]);