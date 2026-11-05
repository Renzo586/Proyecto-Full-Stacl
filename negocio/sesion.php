<?php
// Manejo de sesión PHP y control de acceso por rol
require_once __DIR__ . '/util.php';
function sesion_iniciar(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}
function sesion_usuario(): ?array
{
    sesion_iniciar();
    return $_SESSION['usuario'] ?? null;
}

// Exige estar logueado (401 si no)
function sesion_requerir_login(): array
{
    $usuario = sesion_usuario();
    if (!$usuario) {
        responderJson(['ok' => false, 'error' => 'No autenticado'], 401);
    }
    return $usuario;
}
function sesion_requerir_rol(string ...$roles): array
{
    $usuario = sesion_requerir_login();
    if (!in_array($usuario['rol'], $roles, true)) {
        responderJson(['ok' => false, 'error' => 'No tenés permisos para esta acción'], 403);
    }
    return $usuario;
}