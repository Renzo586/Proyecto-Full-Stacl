<?php
// Historial de traslados, filtrable por paciente y por movil.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/trasladobd.php';
exigirMetodo('GET');
sesion_requerir_rol('administrador', 'operador');
$traslados = (new Trasladobd())->listar(campoGet('paciente'), campoGet('matricula'));
responderJson(['ok' => true, 'traslados' => $traslados]);