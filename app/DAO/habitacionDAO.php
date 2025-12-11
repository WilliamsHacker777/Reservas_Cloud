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

    // Crear nueva habitación
    public function create(Habitacion $habitacion) {
        $sql = "INSERT INTO habitaciones (numero, tipo, precio, descripcion, estado, imagen)
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            $habitacion->numero,
            $habitacion->tipo,
            $habitacion->precio,
            $habitacion->descripcion,
            $habitacion->estado,
            $habitacion->imagen
        ]);

        if ($ok) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Actualizar habitación completa
    public function update(Habitacion $habitacion) {
        $sql = "UPDATE habitaciones 
                SET numero = ?, tipo = ?, precio = ?, descripcion = ?, estado = ?, imagen = ?
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $habitacion->numero,
            $habitacion->tipo,
            $habitacion->precio,
            $habitacion->descripcion,
            $habitacion->estado,
            $habitacion->imagen,
            $habitacion->id
        ]);
    }

    // Eliminar habitación
    public function delete($id) {
        $sql = "DELETE FROM habitaciones WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Verificar si existe una habitación por número
    public function existeNumero($numero, $exceptId = null) {
        $sql = "SELECT COUNT(*) as total FROM habitaciones WHERE numero = ?";
        if ($exceptId) {
            $sql .= " AND id != ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$numero, $exceptId]);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$numero]);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
}