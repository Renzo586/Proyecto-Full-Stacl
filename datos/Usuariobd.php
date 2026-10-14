<?php
// Acceso a datos de usuarios: buscar usuario para el login (admin/paciente) y registrar pacientes.
require_once __DIR__ . '/conexion.php';

class Usuariobd
{
    // Busca la credencial primero en Administrativo (por Usuario) y despues en Paciente (por Cedula).
    // Devuelve un array con nombre, rol e id, o null si no existe.
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
                // El nombre de la columna id puede variar en tu tabla; se prueban las mas comunes.
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

    // Inserta un paciente nuevo. Devuelve false si la cedula ya existe.
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
