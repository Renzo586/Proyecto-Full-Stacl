<?php
// Devuelve las categorias de documentos para el select del formulario.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/documentobd.php';

exigirMetodo('GET');
sesion_requerir_rol('administrador', 'operador');
responderJson(['ok' => true, 'categorias' => (new Documentobd())->listarCategorias()]);