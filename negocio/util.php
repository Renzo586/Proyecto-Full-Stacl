<?php
// Funciones comunes de la capa de negocio.

// Lee un campo de $_POST y le saca los espacios sobrantes.
function campoPost(string $nombre): string
{
    return trim($_POST[$nombre] ?? '');
}

// Lee un parametro de la URL ($_GET) y le saca los espacios sobrantes.
function campoGet(string $nombre): string
{
    return trim($_GET[$nombre] ?? '');
}

// Responde en JSON y termina el script.
function responderJson(array $datos)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// Respuesta de error con uno o mas mensajes.
function responderErrores(array $errores)
{
    responderJson(['ok' => false, 'error' => implode(' ', $errores), 'errores' => $errores]);
}
