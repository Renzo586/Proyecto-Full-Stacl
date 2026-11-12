<?php
// Cambio de estado de un traslado, respetando el ciclo de vida permitido.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/Trasladobd.php';

exigirMetodo('POST');
sesion_requerir_rol('administrador', 'operador');

$id = (int) campoPost('id');
$nuevoEstado = campoPost('estado');
$permitidos = [
    'Pendiente'  => ['En curso', 'Cancelado'],
    'En curso'   => ['Finalizado', 'Cancelado'],
    'Finalizado' => [],
    'Cancelado'  => [],
];
$traslados = new Trasladobd();
$traslado  = $id > 0 ? $traslados->obtenerPorId($id) : null;
if (!$traslado) {
    responderErrores(['El traslado no existe.']);
}
if (!in_array($nuevoEstado, $permitidos[$traslado['Estado']], true)) {
    responderErrores(['No se puede pasar de "' . $traslado['Estado'] . '" a "' . $nuevoEstado . '".']);
}
$idEstado = $traslados->buscarIdEstado($nuevoEstado);
if ($idEstado === null) {
    responderErrores(['Estado invalido.']);
}
$traslados->cambiarEstado($id, $idEstado, $nuevoEstado === 'Finalizado');
responderJson(['ok' => true, 'mensaje' => 'Estado actualizado a "' . $nuevoEstado . '".']);