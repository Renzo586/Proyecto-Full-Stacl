<?php
// Listado de documentos. Requiere sesion (administrador u operador).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/documentobd.php';

exigirMetodo('GET');
sesion_requerir_rol('administrador', 'operador');

responderJson(['ok' => true, 'documentos' => (new documentobd())->listarDocumentos()]);
