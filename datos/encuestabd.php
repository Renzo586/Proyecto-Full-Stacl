<?php
// Acceso a datos de encuestas de satisfaccion.
require_once __DIR__ . '/conexion.php';

class Encuestabd
{
    public function obtenerEncuestaActiva(): ?array
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare("SELECT Id_encuesta, Titulo FROM Encuesta WHERE Activa = 1 ORDER BY Id_encuesta LIMIT 1");
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    public function guardarRespuesta(int $idEncuesta, string $servicio, int $calificacion, int $recomienda, ?string $nombre): bool
    {
        $conexion = obtenerConexion();
        $stmt = $conexion->prepare(
            "INSERT INTO Respuesta_encuesta (Id_encuesta, Servicio, Calificacion, Recomienda, Nombre_opcional)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$idEncuesta, $servicio, $calificacion, $recomienda, $nombre]);
    }
}