<?php
// Acceso a datos de documentos: CRUD completo. Solo se guarda la RUTA del archivo, nunca el binario.
require_once __DIR__ . '/conexion.php';

class Documentobd
{
    public function listarDocumentos(): array
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare(
            "SELECT d.Id_documento, d.Codigo_qr, d.Titulo, d.Descripcion, d.Ruta_archivo,
                    d.Nombre_original, d.Fecha_subida, d.Id_categoria, c.Nombre AS Categoria
             FROM Documento d
             JOIN Categoria_documento c ON c.Id_categoria = d.Id_categoria
             ORDER BY d.Titulo ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorCodigo(string $codigoQr): ?array
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare(
            "SELECT d.Id_documento, d.Codigo_qr, d.Titulo, d.Descripcion, d.Ruta_archivo,
                    d.Nombre_original, d.Fecha_subida, c.Nombre AS Categoria
             FROM Documento d
             JOIN Categoria_documento c ON c.Id_categoria = d.Id_categoria
             WHERE d.Codigo_qr = :codigo"
        );
        $stmt->execute(['codigo' => $codigoQr]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function obtenerPorId(int $id): ?array
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("SELECT * FROM Documento WHERE Id_documento = :id");
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function listarCategorias(): array
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("SELECT Id_categoria, Nombre FROM Categoria_documento ORDER BY Nombre");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Devuelve false si el codigo QR ya existe.
    public function crearDocumento(string $codigoQr, string $titulo, ?string $descripcion,
                                   string $rutaArchivo, ?string $nombreOriginal,
                                   int $idCategoria, int $idUsuario): bool
    {
        $conexion = obtenerConexion();
        $stmtBuscar = $conexion->prepare("SELECT 1 FROM Documento WHERE Codigo_qr = :codigo");
        $stmtBuscar->execute(['codigo' => $codigoQr]);
        if ($stmtBuscar->fetch()) {
            return false;
        }
        $stmt = $conexion->prepare(
            "INSERT INTO Documento (Codigo_qr, Titulo, Descripcion, Ruta_archivo, Nombre_original, Id_categoria, Id_usuario)
             VALUES (:codigo, :titulo, :descripcion, :ruta, :original, :categoria, :usuario)"
        );
        return $stmt->execute([
            'codigo'      => $codigoQr,
            'titulo'      => $titulo,
            'descripcion' => $descripcion,
            'ruta'        => $rutaArchivo,
            'original'    => $nombreOriginal,
            'categoria'   => $idCategoria,
            'usuario'     => $idUsuario,
        ]);
    }

    public function actualizarDocumento(int $id, string $titulo, ?string $descripcion, int $idCategoria): bool
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare(
            "UPDATE Documento SET Titulo = :titulo, Descripcion = :descripcion, Id_categoria = :categoria
             WHERE Id_documento = :id"
        );
        return $stmt->execute([
            'titulo' => $titulo, 'descripcion' => $descripcion, 'categoria' => $idCategoria, 'id' => $id,
        ]);
    }

    // Borra el registro y devuelve la ruta del archivo para que la capa de negocio lo elimine del disco.
    public function eliminarDocumento(int $id): ?string
    {
        $documento = $this->obtenerPorId($id);
        if (!$documento) {
            return null;
        }
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("DELETE FROM Documento WHERE Id_documento = :id");
        $stmt->execute(['id' => $id]);
        return $documento['Ruta_archivo'];
    }
}
