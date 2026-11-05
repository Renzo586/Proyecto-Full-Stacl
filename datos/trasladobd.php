<?php
// Acceso a datos de traslados de ambulancias. Todas las consultas son preparadas.
require_once __DIR__ . '/conexion.php';
class Trasladobd
{
    private const CONSULTA = "SELECT t.Id_traslado, t.Origen, t.Destino, t.H_salida, t.H_llegada, t.Observaciones,
            t.Cedula_paciente, CONCAT(p.Nombre, ' ', p.Apellido) AS Paciente,
            t.Matricula, t.Cedula_chofer, CONCAT(pc.Nombre, ' ', pc.Apellido) AS Chofer,
            t.Cedula_enfermero, CONCAT(pe.Nombre, ' ', pe.Apellido) AS Enfermero,
            e.Nombre AS Estado
        FROM Traslado t
        JOIN Paciente p ON p.Cedula = t.Cedula_paciente
        JOIN Estado_traslado e ON e.Id_estado = t.Id_estado
        JOIN Personal pc ON pc.Cedula = t.Cedula_chofer
        JOIN Personal pe ON pe.Cedula = t.Cedula_enfermero";
    public function listar(string $paciente, string $matricula): array
    {
        $condiciones = [];
        $parametros  = [];

        if ($paciente !== '') {
            $condiciones[] = "(p.Cedula = ? OR CONCAT(p.Nombre, ' ', p.Apellido) LIKE ?)";
            $parametros[]  = $paciente;
            $parametros[]  = '%' . $paciente . '%';
        }
        if ($matricula !== '') {
            $condiciones[] = "t.Matricula = ?";
            $parametros[]  = $matricula;
        }
        $sql = self::CONSULTA;
        if ($condiciones) {
            $sql .= ' WHERE ' . implode(' AND ', $condiciones);
        }
        $sql .= ' ORDER BY t.H_salida DESC';

        $stmt = obtenerConexion()->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }
    public function obtenerPorId(int $id): ?array
    {
        $stmt = obtenerConexion()->prepare(
            "SELECT t.*, e.Nombre AS Estado
             FROM Traslado t JOIN Estado_traslado e ON e.Id_estado = t.Id_estado
             WHERE t.Id_traslado = ?"
        );
        $stmt->execute([$id]);
        $fila = $stmt->fetch();
        return $fila ?: null;
    }
    public function buscarIdEstado(string $nombre): ?int
    {
        $stmt = obtenerConexion()->prepare("SELECT Id_estado FROM Estado_traslado WHERE Nombre = ?");
        $stmt->execute([$nombre]);
        $fila = $stmt->fetch();
        return $fila ? (int) $fila['Id_estado'] : null;
    }
    public function crear(array $d, int $idEstado, int $idUsuario): bool
    {
        $stmt = obtenerConexion()->prepare(
            "INSERT INTO Traslado (Cedula_paciente, Matricula, Cedula_chofer, Cedula_enfermero, Origen, Destino,
                                   H_salida, H_llegada, Id_estado, Id_usuario, Observaciones)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $d['paciente'], $d['matricula'], $d['chofer'], $d['enfermero'], $d['origen'], $d['destino'],
            $d['salida'], $d['llegada'], $idEstado, $idUsuario, $d['observaciones'],
        ]);
    }
    public function actualizar(int $id, array $d): bool
    {
        $stmt = obtenerConexion()->prepare(
            "UPDATE Traslado SET Cedula_paciente = ?, Matricula = ?, Cedula_chofer = ?, Cedula_enfermero = ?,
                    Origen = ?, Destino = ?, H_salida = ?, H_llegada = ?, Observaciones = ?
             WHERE Id_traslado = ?"
        );
        return $stmt->execute([
            $d['paciente'], $d['matricula'], $d['chofer'], $d['enfermero'], $d['origen'], $d['destino'],
            $d['salida'], $d['llegada'], $d['observaciones'], $id,
        ]);
    }
    public function cambiarEstado(int $id, int $idEstado, bool $marcarLlegada): bool
    {
        $stmt = obtenerConexion()->prepare(
            "UPDATE Traslado
             SET Id_estado = ?, H_llegada = IF(? = 1 AND H_llegada IS NULL, NOW(), H_llegada)
             WHERE Id_traslado = ?"
        );
        return $stmt->execute([$idEstado, $marcarLlegada ? 1 : 0, $id]);
    }

    // Datos para llenar los selects del formulario.
    public function listarOpciones(): array
    {
        $conexion = obtenerConexion();
        $consultas = [
            'pacientes'   => "SELECT Cedula, CONCAT(Nombre, ' ', Apellido) AS Nombre FROM Paciente ORDER BY Apellido, Nombre",
            'ambulancias' => "SELECT Matricula, Modelo FROM Ambulancia WHERE Activa = 1 ORDER BY Matricula",
            'choferes'    => "SELECT p.Cedula, CONCAT(p.Nombre, ' ', p.Apellido) AS Nombre
                              FROM Chofer c JOIN Personal p ON p.Cedula = c.Cedula ORDER BY p.Apellido",
            'enfermeros'  => "SELECT p.Cedula, CONCAT(p.Nombre, ' ', p.Apellido) AS Nombre
                              FROM Enfermero n JOIN Personal p ON p.Cedula = n.Cedula ORDER BY p.Apellido",
        ];
        $opciones = [];
        foreach ($consultas as $clave => $sql) {
            $stmt = $conexion->prepare($sql);
            $stmt->execute();
            $opciones[$clave] = $stmt->fetchAll();
        }
        return $opciones;
    }
}