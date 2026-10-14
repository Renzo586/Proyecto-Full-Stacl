<?php
// Acceso a datos de documentos: listar, buscar por QR, crear y eliminar.
require_once __DIR__ . '/conexion.php';

class Documentobd
{
    public function listarDocumentos()
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("SELECT * FROM Documento ORDER BY Titulo ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorCodigo($codigoQR)
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("SELECT * FROM Documento WHERE CodigoQR = :codigo");
        $stmt->execute(['codigo' => $codigoQR]);
        return $stmt->fetch();
    }

    public function crearDocumento($codigoQR, $titulo, $descripcion, $idAdministrativo)
    {
        $conexion = obtenerConexion();
        $stmtBuscar = $conexion->prepare("SELECT * FROM Documento WHERE CodigoQR = :codigo");
        $stmtBuscar->execute(['codigo' => $codigoQR]);
        if ($stmtBuscar->fetch()) {
            return false;
        }
        $stmt = $conexion->prepare(
            "INSERT INTO Documento (CodigoQR, Titulo, Descripcion, Id_administrativo)
             VALUES (:codigo, :titulo, :descripcion, :id_admin)"
        );
        return $stmt->execute([
            'codigo'      => $codigoQR,
            'titulo'      => $titulo,
            'descripcion' => $descripcion,
            'id_admin'    => $idAdministrativo,
        ]);
    }

    public function eliminarDocumento($codigoQR)
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("DELETE FROM Documento WHERE CodigoQR = :codigo");
        return $stmt->execute(['codigo' => $codigoQR]);
    }
}
