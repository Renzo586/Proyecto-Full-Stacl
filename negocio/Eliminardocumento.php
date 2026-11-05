<?php
// Baja de documento: solo administrador. Elimina el registro y el archivo fisico.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/documentobd.php';
exigirMetodo('POST');
sesion_requerir_rol('administrador');
$id = (int) campoPost('id');
if ($id <= 0) {
    responderErrores(['Documento invalido.']);
}
$ruta = (new Documentobd())->eliminarDocumento($id);
if ($ruta === null) {
    responderErrores(['El documento no existe.']);
}
$nombreArchivo = basename($ruta);
$rutaArchivo   = __DIR__ . '/../uploads/documentos/' . $nombreArchivo;
if (file_exists($rutaArchivo)) {
    unlink($rutaArchivo);
}
responderJson(['ok' => true, 'mensaje' => 'Documento eliminado.']);
