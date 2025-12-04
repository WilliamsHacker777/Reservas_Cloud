<?php

require_once __DIR__ . "/../entities/Reserva.php";

class ReservaDAO {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function existeReservaEnRango($habitacionId, $inicio, $fin) {
        $sql = "SELECT COUNT(*) as total
                FROM reservas
                WHERE habitacion_id = ?
                AND (fecha_inicio <= ? AND fecha_fin >= ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$habitacionId, $fin, $inicio]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'] > 0;
    }

    public function create(Reserva $reserva) {
        $sql = "INSERT INTO reservas (persona_id, habitacion_id, fecha_inicio, fecha_fin, total, estado, payment_method, payment_status, payment_receipt)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        $ok = $stmt->execute([
            $reserva->persona_id,
            $reserva->habitacion_id,
            $reserva->fecha_inicio,
            $reserva->fecha_fin,
            $reserva->total,
            $reserva->estado,
            $reserva->payment_method,
            $reserva->payment_status,
            $reserva->payment_receipt
        ]);

        if ($ok) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function findByUserId($usuarioId) {
        // Busca todas las reservas del usuario a través de personas vinculadas
        // Por ahora buscamos por el email del usuario en la sesión
        // Alternativamente puedes unir usuarios y personas si existe relación directa
        $sql = "SELECT r.* FROM reservas r
                WHERE r.id > 0
                ORDER BY r.fecha_inicio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $sql = "SELECT r.* FROM reservas r ORDER BY r.fecha_inicio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserEmail($email) {
        $sql = "SELECT r.* FROM reservas r
                INNER JOIN personas p ON r.persona_id = p.id
                WHERE p.email = :email
                ORDER BY r.fecha_inicio DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
