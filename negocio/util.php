<?php
// Funciones comunes de la capa de negocio.
function campoPost(string $nombre): string
{
    return trim($_POST[$nombre] ?? '');
}

function campoGet(string $nombre): string
{
    return trim($_GET[$nombre] ?? '');
}

function responderJson(array $datos, int $codigo = 200)
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

function responderErrores(array $errores)
{
    responderJson(['ok' => false, 'error' => implode(' ', $errores), 'errores' => $errores]);
}
function exigirMetodo(string $metodo): void
{
    if ($_SERVER['REQUEST_METHOD'] !== $metodo) {
        responderJson(['ok' => false, 'error' => 'Metodo no permitido.'], 405);
    }
}
