    <?php

    require_once __DIR__ . "/../entities/Usuario.php";

    class UsuarioDAO {

        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function findByEmail($email) {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $u = new Usuario();
                $u->id = $row["id"];
                $u->email = $row["email"];
                $u->password = $row["password"];
                $u->rol = $row["rol"] ?? 'user';
                $u->activo = $row["activo"] ?? 1;
                return $u;
            }
            return null;
        }

        public function save( $email, $password) {
        $sql = "INSERT INTO usuarios(email, password)
        VALUES ( :email, :password)";


        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":email"  => $email,
            ":password" => $password
        ]);
    }

    }
