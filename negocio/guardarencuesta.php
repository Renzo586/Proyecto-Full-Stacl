<?php
// Guarda una respuesta de encuesta. No requiere sesion (formulario anonimo).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/encuestabd.php';

exigirMetodo('POST');

$servicio     = campoPost('servicio');
$calificacion = campoPost('calificacion');
$recomienda   = campoPost('recomienda');
$nombre       = campoPost('nombre');

$errores = [];
if ($servicio === '' || strlen($servicio) > 100) {
    $errores[] = 'El servicio es obligatorio (maximo 100 caracteres).';
}
if (!ctype_digit($calificacion) || (int) $calificacion < 1 || (int) $calificacion > 5) {
    $errores[] = 'La calificacion tiene que ser un numero del 1 al 5.';
}
if ($recomienda !== '1' && $recomienda !== '0') {
    $errores[] = 'Indica si recomendarias el servicio.';
}
if (strlen($nombre) > 100) {
    $errores[] = 'El nombre no puede superar los 100 caracteres.';
}
if ($errores) {
    responderErrores($errores);
}

$encuestas = new Encuestabd();
$encuesta  = $encuestas->obtenerEncuestaActiva();
if (!$encuesta) {
    responderErrores(['No hay ninguna encuesta activa en este momento.']);
}

$encuestas->guardarRespuesta(
    (int) $encuesta['Id_encuesta'],
    $servicio,
    (int) $calificacion,
    (int) $recomienda,
    $nombre === '' ? null : $nombre
);
responderJson(['ok' => true, 'mensaje' => 'Gracias por responder la encuesta.']);