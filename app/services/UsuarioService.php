<?php

require_once __DIR__ . '/../DAO/UsuarioDAO.php';
require_once __DIR__ . '/../core/database.php';

class UsuarioService {

    private $usuarioDAO;

    public function __construct($conn) {
        $this->usuarioDAO = new UsuarioDAO($conn);
    }

    public function login($email, $password) {

        $usuario = $this->usuarioDAO->findByEmail($email);

        if (!$usuario) return false;

        if (password_verify($password, $usuario->password)) {
            return $usuario;
        }

        return false;
    }

    public function registrar($email, $password)
    {
        // Verificar si existe email
        if ($this->usuarioDAO->findByEmail($email)) {
            return false;
        }

        $hashed = password_hash($password, PASSWORD_BCRYPT);

        return $this->usuarioDAO->save( $email, $hashed);
    }
}

