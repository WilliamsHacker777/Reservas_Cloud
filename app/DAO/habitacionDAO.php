<?php

require_once __DIR__ . "/../entities/Habitacion.php";

class HabitacionDAO {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Listar todas las habitaciones en formato array
    public function findAll() {
        $sql = "SELECT * FROM habitaciones";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar habitación por ID en formato array
    public function findById($id) {
        $sql = "SELECT * FROM habitaciones WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar estado de habitación (0,1,2)
    public function updateEstado($id, $estado) {
        $sql = "UPDATE habitaciones SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':estado' => $estado, ':id' => $id]);
    }
}