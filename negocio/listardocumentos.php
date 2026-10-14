<?php
// Listado de documentos (ordenado por titulo en la capa de datos).
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/../datos/documentobd.php';

header('Access-Control-Allow-Methods: GET');

$documentos = (new Documentobd())->listarDocumentos();
responderJson(['ok' => true, 'documentos' => $documentos]);
