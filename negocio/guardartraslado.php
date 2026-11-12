<?php
// Alta y edicion de traslados. Si llega un id se edita; si no, se crea en estado Pendiente.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/trasladobd.php';
exigirMetodo('POST');
$usuario = sesion_requerir_rol('administrador', 'operador');
function leerFecha(string $texto): ?DateTime
{
    $fecha  = DateTime::createFromFormat('Y-m-d\TH:i', $texto);
    $avisos = DateTime::getLastErrors();
    if (!$fecha || ($avisos && ($avisos['warning_count'] > 0 || $avisos['error_count'] > 0))) {
        return null;
    }
    return $fecha;
}
$id = (int) campoPost('id');
$d = [
    'paciente'      => campoPost('paciente'),
    'matricula'     => campoPost('matricula'),
    'chofer'        => campoPost('chofer'),
    'enfermero'     => campoPost('enfermero'),
    'origen'        => campoPost('origen'),
    'destino'       => campoPost('destino'),
    'salida'        => campoPost('salida'),
    'llegada'       => campoPost('llegada'),
    'observaciones' => campoPost('observaciones'),
];
$errores = [];
foreach (['paciente' => 'paciente', 'matricula' => 'movil', 'chofer' => 'chofer', 'enfermero' => 'enfermero'] as $campo => $nombre) {
    if ($d[$campo] === '') {
        $errores[] = "Elegi el $nombre.";
    }
}
if ($d['origen'] === '' || strlen($d['origen']) > 150) {
    $errores[] = 'El origen es obligatorio (maximo 150 caracteres).';
}
if ($d['destino'] === '' || strlen($d['destino']) > 150) {
    $errores[] = 'El destino es obligatorio (maximo 150 caracteres).';
}
if (strlen($d['observaciones']) > 1000) {
    $errores[] = 'Las observaciones no pueden superar los 1000 caracteres.';
}
$salida  = leerFecha($d['salida']);
$llegada = $d['llegada'] === '' ? null : leerFecha($d['llegada']);
if (!$salida) {
    $errores[] = 'La hora de salida no es valida.';
}
if ($d['llegada'] !== '' && !$llegada) {
    $errores[] = 'La hora de llegada no es valida.';
}
if ($salida && $llegada && $llegada < $salida) {
    $errores[] = 'La llegada no puede ser anterior a la salida.';
}
if ($errores) {
    responderErrores($errores);
}
$d['salida']        = $salida->format('Y-m-d H:i:s');
$d['llegada']       = $llegada ? $llegada->format('Y-m-d H:i:s') : null;
$d['observaciones'] = $d['observaciones'] === '' ? null : $d['observaciones'];

$traslados = new Trasladobd();
try {
    if ($id > 0) {
        $actual = $traslados->obtenerPorId($id);
        if (!$actual) {
            responderErrores(['El traslado no existe.']);
        }
        if ($actual['Estado'] === 'Finalizado' || $actual['Estado'] === 'Cancelado') {
            responderErrores(['No se puede editar un traslado finalizado o cancelado.']);
        }
        $traslados->actualizar($id, $d);
        $mensaje = 'Traslado actualizado con exito.';
    } else {
        $traslados->crear($d, $traslados->buscarIdEstado('Pendiente'), $usuario['id']);
        $mensaje = 'Traslado registrado con exito.';
    }
} catch (PDOException $e) {
    responderErrores(['No se pudo guardar. Revisa que paciente, movil, chofer y enfermero existan.']);
}
responderJson(['ok' => true, 'mensaje' => $mensaje]);