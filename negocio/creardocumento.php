<?php
// Alta de documento: guarda el archivo en el servidor y SOLO la ruta en la base de datos.
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/../datos/documentobd.php';
exigirMetodo('POST');
$usuario = sesion_requerir_rol('administrador', 'operador');
$codigoQR    = campoPost('codigoQR');
$titulo      = campoPost('titulo');
$descripcion = campoPost('descripcion');
$idCategoria = (int) campoPost('idCategoria');
$archivo     = $_FILES['archivo'] ?? null;
$tamanoMaximo    = 2 * 1024 * 1024; // 2 MB
$tiposPermitidos = ['application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png'];
$errores = [];

if (!preg_match('/^[A-Za-z0-9_-]{3,64}$/', $codigoQR)) {
    $errores[] = 'El codigo QR debe tener entre 3 y 64 caracteres (letras, numeros, guion o guion bajo).';
}
if ($titulo === '' || strlen($titulo) > 150) {
    $errores[] = 'El titulo es obligatorio (maximo 150 caracteres).';
}
if (strlen($descripcion) > 1000) {
    $errores[] = 'La descripcion no puede superar los 1000 caracteres.';
}
if ($idCategoria <= 0) {
    $errores[] = 'Elegi una categoria.';
}
$extension = '';
if (!$archivo || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
    $errores[] = 'Selecciona un archivo.';
} elseif ($archivo['error'] === UPLOAD_ERR_INI_SIZE || $archivo['error'] === UPLOAD_ERR_FORM_SIZE) {
    $errores[] = 'El archivo es demasiado grande (maximo 2 MB).';
} elseif ($archivo['error'] !== UPLOAD_ERR_OK) {
    $errores[] = 'No se pudo subir el archivo.';
} elseif ($archivo['size'] > $tamanoMaximo) {
    $errores[] = 'El archivo es demasiado grande (maximo 2 MB).';
} else {
    $tipo = mime_content_type($archivo['tmp_name']);
    if (!isset($tiposPermitidos[$tipo])) {
        $errores[] = 'Solo se permiten archivos PDF, JPG o PNG.';
    } else {
        $extension = $tiposPermitidos[$tipo];
    }
}
if ($errores) {
    responderErrores($errores);
}
$documentos = new Documentobd();
if ($documentos->obtenerPorCodigo($codigoQR) !== null) {
    responderErrores(["Ya existe un documento con el codigo QR \"$codigoQR\"."]);
}
$carpeta = __DIR__ . '/../uploads/documentos/';
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0755, true);
}
$nombreGuardado = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

if (!move_uploaded_file($archivo['tmp_name'], $carpeta . $nombreGuardado)) {
    responderErrores(['No se pudo guardar el archivo en el servidor.']);
}

// En la base se guarda solo la ruta relativa.
$rutaRelativa   = 'uploads/documentos/' . $nombreGuardado;
$nombreOriginal = mb_substr(basename($archivo['name']), 0, 255);

try {
    $documentos->crearDocumento(
        $codigoQR,
        $titulo,
        $descripcion === '' ? null : $descripcion,
        $rutaRelativa,
        $nombreOriginal,
        $idCategoria,
        $usuario['id']
    );
} catch (PDOException $e) {
    unlink($carpeta . $nombreGuardado);
    responderErrores(['No se pudo guardar el documento. Revisa que la categoria sea valida.']);
}

responderJson(['ok' => true, 'mensaje' => 'Documento creado con exito.']);