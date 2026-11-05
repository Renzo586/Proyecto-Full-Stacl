<?php
// Devuelve pacientes, ambulancias, choferes y enfermeros para los selects.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/Trasladobd.php';
exigirMetodo('GET');
sesion_requerir_rol('administrador', 'operador');
responderJson(['ok' => true, 'opciones' => (new Trasladobd())->listarOpciones()]);