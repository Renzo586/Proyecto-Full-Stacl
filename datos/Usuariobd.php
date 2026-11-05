<?php
// Acceso a datos de usuarios (administrador / operador) y registro de pacientes.
require_once __DIR__ . '/conexion.php';

class Usuariobd
{
    public function buscarPorUsuario(string $usuario): ?array
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare(
            "SELECT Id_usuario, Usuario, Clave_hash, Nombre, Apellido, Rol, Activo
             FROM Usuario WHERE Usuario = :usuario"
        );
        $stmt->execute(['usuario' => $usuario]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function registrarPaciente(string $nombre, string $apellido, string $cedula, string $telefono): bool
    {
        $conexion = obtenerConexion();

        $stmtBuscar = $conexion->prepare("SELECT 1 FROM Paciente WHERE Cedula = :cedula");
        $stmtBuscar->execute(['cedula' => $cedula]);
        if ($stmtBuscar->fetch()) {
            return false;
        }

        $stmtInsertar = $conexion->prepare(
            "INSERT INTO Paciente (Cedula, Nombre, Apellido, Telefono)
             VALUES (:cedula, :nombre, :apellido, :telefono)"
        );
        return $stmtInsertar->execute([
            'cedula'   => $cedula,
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
        ]);
    }
}

