<?php
// Acceso a datos de usuarios: buscar usuario para el login (admin/paciente) y registrar pacientes.
require_once __DIR__ . '/conexion.php';

class Usuariobd
{
    public function buscarUsuario(string $credencial): ?array
    {
        $conexion = obtenerConexion();

        $stmtAdmin = $conexion->prepare("SELECT * FROM Administrativo WHERE Usuario = :credencial");
        $stmtAdmin->execute(['credencial' => $credencial]);
        $admin = $stmtAdmin->fetch();
        if ($admin) {
            return [
                'nombre' => $admin['Nombre'],
                'rol'    => 'admin',
                'id'     => $admin['Id_administrativo'] ?? $admin['Id'] ?? $admin['id'] ?? null,
            ];
        }

        $stmtPaciente = $conexion->prepare("SELECT * FROM Paciente WHERE Cedula = :credencial");
        $stmtPaciente->execute(['credencial' => $credencial]);
        $paciente = $stmtPaciente->fetch();
        if ($paciente) {
            return [
                'nombre' => $paciente['Nombre'],
                'rol'    => 'paciente',
                'id'     => $paciente['Cedula'],
            ];
        }

        return null;
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
