<?php
// Cierre de sesion real: destruye la sesion en el servidor.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';

exigirMetodo('POST');
sesion_iniciar();

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

responderJson(['ok' => true]);